<?php

namespace AviationCode\Elasticsearch\Query;

use AviationCode\Elasticsearch\ElasticsearchClient;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class Builder
{
    use ElasticsearchClient;

    /** @var ElasticSearchable|Model */
    private $model;

    private $size = 100;
    private $sort = [];

    public function __construct($model = null)
    {
        $this->model = $model;
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

    public function latest(string $field)
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

    public function get()
    {
        $response = $this->getClient()->search([
            'index' => $this->model->getIndexName(),
            'body' => [
                'size' => $this->size,
                'sort' => $this->sort,
            ],
        ]);

        $items = array_map(function ($item) {
            return $this->model->newFromElasticBuilder($item);
        }, $response['hits']['hits']);

        return new Collection($items);
    }
}
