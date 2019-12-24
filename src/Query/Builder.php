<?php

namespace AviationCode\Elasticsearch\Query;

use AviationCode\Elasticsearch\ElasticsearchClient;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
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
     * @var array
     */
    private $aggregations = [];

    public function __construct($model = null)
    {
        $this->model = $model;
        $this->query = new Query();

        if (is_string($this->model)) {
            $this->model = new $this->model;
        }
    }

    /**
     * Find model by id.
     *
     * @param $id
     *
     * @return Model|null
     */
    public function find($id):? Model
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
     * Perform a raw elastic query.
     *
     * @param array $query
     * @param string|null $index
     * @return array
     */
    public function raw(array $query = [], ?string $index = null): array
    {
        return $this->getClient()->search([
            'index' => $index ?? $this->model->getIndexName(),
            'body' => $query,
        ]);
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
            'aggs' => $this->aggregations,
        ]), $this->model);
    }
}
