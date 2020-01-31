<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

abstract class Bucket implements Arrayable
{
    use HasAggregations;

    /**
     * @var array
     */
    protected $allowedOptions = [];

    /**
     * Bucket constructor.
     *
     * @param string $key
     * @param string $model
     */
    public function __construct(string $key, string $model)
    {
        $this->key = $key;
        $this->model = $model;
        $this->aggregations = new Aggregation();
    }

    /**
     * Only return the options which are allowed.
     *
     * @param array $options
     * @return array
     */
    protected function allowedOptions(array $options): array
    {
        return Arr::only($options, $this->allowedOptions);
    }

    /**
     * Return array structure of the object as an elastic query.
     *
     * @return array
     */
    abstract protected function toElastic(): array;
}
