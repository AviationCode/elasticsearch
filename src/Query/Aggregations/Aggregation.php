<?php

namespace AviationCode\Elasticsearch\Query\Aggregations;

use Illuminate\Contracts\Support\Arrayable;

class Aggregation implements Arrayable
{
    /**
     * List of all aggregations.
     *
     * @var array
     */
    private $aggregations = [];

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_map(function ($aggregation) {
            return $aggregation->toArray();
        }, $this->aggregations);
    }
}
