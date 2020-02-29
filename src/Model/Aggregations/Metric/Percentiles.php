<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;

class Percentiles extends Item
{
    /**
     * Percentiles constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes['values']);
    }
}
