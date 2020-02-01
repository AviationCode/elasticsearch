<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class GeoCentroid.
 *
 * @property array $value;
 */
class GeoCentroid extends Fluent
{
    /**
     * GeoCentroid Aggregation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes['location'] = new Fluent($attributes['location']);

        parent::__construct($attributes);
    }
}
