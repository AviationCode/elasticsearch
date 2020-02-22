<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Percentiles extends Fluent
{
    /**
     * Percentiles constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct(Arr::get($attributes, 'values', []));
    }
}
