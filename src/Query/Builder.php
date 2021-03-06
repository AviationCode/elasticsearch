<?php

namespace AviationCode\Elasticsearch\Query;

use AviationCode\Elasticsearch\ElasticsearchClient;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Model\ElasticHit;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use AviationCode\Elasticsearch\Pagination\SimplePaginator;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\Paginator;

class Builder
{
    use ElasticsearchClient;

    /**
     * @var ElasticSearchable|mixed
     */
    private $model;

    /**
     * The maximum number of documents to return.
     *
     * @var int
     */
    private $size = 100;

    /**
     * The offset to use limited to max-window size.
     *
     * @var int
     */
    private $from = 0;

    /**
     * Sorting fields.
     *
     * @var array
     */
    private $sort = [];

    /**
     * Query context applied to this query.
     *
     * @var Query
     */
    private $query;

    /**
     * Aggregations applied to the query.
     *
     * @var Aggregation
     */
    private $aggregations;

    /**
     * @var array|string[]|null
     */
    private $fields;

    /**
     * Builder constructor.
     * @param ElasticSearchable|mixed|string|null $model
     */
    public function __construct($model = null)
    {
        $this->query = new Query();
        $this->aggregations = new Aggregation();

        if (is_string($model) && class_exists($model)) {
            $this->model = new $model();
        } elseif (!is_object($model)) {
            $this->model = ElasticHit::onIndex($model);
        } else {
            $this->model = $model;
        }
    }

    /**
     * Find model by id.
     *
     * @param string|int $id
     *
     * @return Model|null
     */
    public function find($id): ?Model
    {
        $response = $this->getClient()->get(['index' => $this->model->getIndexName(), 'id' => $id]);

        if (!$response['found']) {
            return null;
        }

        return $this->model->newFromElasticBuilder($response);
    }

    /**
     * Find by id or throw model not found exception if missing.
     *
     * @param string|int $id
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model
    {
        $model = $this->find($id);

        if (!$model) {
            throw new ModelNotFoundException("$id not found");
        }

        return $model;
    }

    /**
     * Order by latest.
     *
     * @param string $field
     * @return $this
     */
    public function latest(string $field = 'created_at')
    {
        return $this->orderBy($field, 'desc');
    }

    /**
     * Order by field and direction.
     *
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, $direction = 'asc')
    {
        $this->sort[] = [$field => $direction];

        return $this;
    }

    /**
     * @param array|string[] $fields
     * @return Builder
     */
    public function select(array $fields = ['*'])
    {
        if (in_array('*', $fields)) {
            $this->fields = null;
        }

        $this->fields = $fields;

        return $this;
    }

    /**
     * Limit the number of results returned.
     *
     * @param int $size
     * @return $this
     */
    public function limit(int $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function skip(int $offset)
    {
        return $this->from($offset);
    }

    /**
     * @param int $from
     * @return $this
     */
    public function from(int $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get first result.
     *
     * @return Model|null
     */
    public function first()
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    /**
     * @param null $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param null $page
     * @return mixed|object
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?: ($this->model ? $this->model->getPerPage() : 100);
        $currentPage = $page ?: Paginator::resolveCurrentPage($pageName);
        $items = $this->skip($perPage * ($currentPage - 1))
            ->limit($perPage)
            ->get();

        return new SimplePaginator($items, $perPage, $currentPage, $pageName);
    }

    /**
     * Get the root must clause.
     *
     * @param \Closure $callback
     *
     * @return Builder
     */
    public function must(\Closure $callback): self
    {
        $this->query->must($callback);

        return $this;
    }

    /**
     * Get the root filter clause.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function filter(\Closure $callback): self
    {
        $this->query->filter($callback);

        return $this;
    }

    /**
     * Chunk the results of a query by comparing IDs.
     * Bypasses the doc count limit.
     *
     * @param int $count
     * @param callable $callback
     * @param string $column
     *
     * @return bool
     */
    public function chunk(int $count, callable $callback, string $column = 'id'): bool
    {
        return $this->chunkById($count, $callback, $column);
    }

    /**
     * Chunk the results of a query by comparing IDs.
     * Bypasses the doc count limit.
     *
     * @param int $count
     * @param callable $callback
     * @param string $column
     *
     * @return bool
     */
    public function chunkById(int $count, callable $callback, string $column = 'id'): bool
    {
        $lastId = null;

        do {
            $clone = clone $this;

            // Reset sort
            $clone->sort = [];
            $clone->orderBy(optional($this->model)->getKeyName() ?? $column, 'asc');
            $clone->limit($count);

            if (! is_null($lastId)) {
                $clone->filter(function (Filter $filter) use ($lastId) {
                    $filter->range('id', 'gt', $lastId);
                });
            }

            $results = $clone->get();

            $countResults = $results->count();

            if ($countResults == 0) {
                break;
            }

            // On each chunk result set, we will pass them to the callback and then let the
            // developer take care of everything within the callback, which allows us to
            // keep the memory low for spinning through large result sets for working.
            if ($callback($results) === false) {
                return false;
            }

            $lastId = $results->last()->$column;

            unset($results);
        } while ($countResults == $count);

        return true;
    }

    /**
     * Using chunk by id loop over all records.
     *
     * @param callable $callback
     * @param int $count
     * @param string $column
     *
     * @return bool
     */
    public function eachById(callable $callback, $count = 1000, $column = 'id'): bool
    {
        return $this->chunkById($count, function ($results) use ($callback) {
            foreach ($results as $key => $value) {
                if ($callback($value, $key) === false) {
                    return false;
                }
            }
        }, $column);
    }

    /**
     * Start building aggregations.
     *
     * @return Aggregation
     */
    public function aggregations(): Aggregation
    {
        return $this->aggregations;
    }

    /**
     * Return the Dsl Query object.
     *
     * @return \AviationCode\Elasticsearch\Query\Dsl\Query
     */
    public function query(): Query
    {
        return $this->query;
    }

    /**
     * Perform a raw elastic query.
     *
     * @param array $query
     * @param string|null $index
     * @param array $params
     * @return array
     */
    public function raw(array $query = [], ?string $index = null, array $params = []): array
    {
        return $this->getClient()->search(array_merge([
            'index' => $index ?? $this->model->getIndexName(),
            'body' => array_filter($query, function ($value) {
                return !empty($value) || ($value === 0);
            }),
        ], $params));
    }

    /**
     * Execute query.
     *
     * @return ElasticCollection
     */
    public function get()
    {
        return ElasticCollection::parse($this->raw([
            'from' => $this->from,
            'size' => $this->size,
            'query' => $this->query->toArray(),
            'sort' => $this->sort,
            'aggs' => $this->aggregations->toArray(),
            '_source' => $this->fields,
        ], $this->model->getIndexName(), ['typed_keys' => true]), $this->model);
    }

    public function count(): int
    {
        return (int) ($this->getClient()->count(
            [
                'index' => $this->model->getIndexName(),
                'body' => array_filter(['query' => $this->query->toArray()]),
            ]
        )['count']);
    }

    /**
     * Force a clone of the underlying query builder when cloning.
     *
     * @return void
     */
    public function __clone()
    {
        $this->query = clone $this->query;
    }
}
