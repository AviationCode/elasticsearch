<?php

namespace AviationCode\Elasticsearch\Query;

use AviationCode\Elasticsearch\ElasticsearchClient;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Model\ElasticHit;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Dsl\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Builder
{
    use ElasticsearchClient;

    /** @var ElasticSearchable|Model */
    private $model;

    /**
     * The maximum number of documents to return.
     *
     * @var int
     */
    private $size = 100;

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

    public function __construct($model = null)
    {
        $this->model = $model;
        $this->query = new Query();
        $this->aggregations = new Aggregation();

        if (is_string($model) && class_exists($model)) {
            $this->model = new $model;
        } else if (! is_object($model)) {
            $this->model = ElasticHit::onIndex($model);
        }
    }

    /**
     * Find model by id.
     *
     * @param $id
     *
     * @return Model|null
     */
    public function find($id): ?Model
    {
        $response = $this->getClient()->get(['index' => $this->model->getIndexName(), 'id' => $id]);

        if (! $response['found']) {
            return null;
        }

        return $this->model->newFromElasticBuilder($response);
    }

    /**
     * Find by id or throw model not found exception if missing.
     *
     * @param $id
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model
    {
        $model = $this->find($id);

        if (! $model) {
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
     * Limit the number of results returned.
     *
     * @param $size
     * @return $this
     */
    public function limit($size)
    {
        $this->size = $size;

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
     * Start building aggregations.
     *
     * @return Aggregation
     */
    public function aggregations(): Aggregation
    {
        return $this->aggregations;
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
                return  !empty($value) || ($value === 0);
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
            'size' => $this->size,
            'query' => $this->query->toArray(),
            'sort' => $this->sort,
            'aggs' => $this->aggregations->toArray(),
        ], $this->model->getIndexName(), ['typed_keys' => true]), $this->model);
    }
}
