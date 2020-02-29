<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;

/**
 * Class GeoBounds.
 *
 * @property Item $top_left;
 * @property Item $bottom_right;
 */
class GeoBounds extends Item
{
    /**
     * GeoBounds Aggregation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes['bounds']);
    }
}
