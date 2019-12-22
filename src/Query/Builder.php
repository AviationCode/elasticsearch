<?php

namespace AviationCode\Elasticsearch\Query;

use AviationCode\Elasticsearch\ElasticsearchClient;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Elasticsearch\Common\Exceptions\Missing404Exception;
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
     * @var array
     */
    private $query = [];

    /**
     * Aggregations applied to the query.
     *
     * @var array
     */
    private $aggregations = [];

    public function __construct($model = null)
    {
        $this->model = $model;

        if (is_string($this->model)) {
            $this->model = new $this->model;
        }
    }

    public function find($id)
    {
        try {
            return $this->model->newFromElasticBuilder(
                $this->getClient()->get(['index' => $this->model->getIndexName(), 'id' => $id])
            );
        } catch (Missing404Exception $exception) {
            throw new ModelNotFoundException(get_class($this->model).' Not found');
        }
    }

    public function latest(string $field = 'created_at')
    {
        return $this->orderBy($field, 'desc');
    }

    public function orderBy(string $field, $direction = 'asc')
    {
        $this->sort[] = [$field => $direction];

        return $this;
    }

    public function limit($size)
    {
        $this->size = $size;

        return $this;
    }

    public function first()
    {
        return $this->get()[0] ?? null;
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
            'query' => [
                'bool' => $this->query,
            ],
            'sort' => $this->sort,
            'aggs' => $this->aggregations,
        ]), $this->model);
    }
}
