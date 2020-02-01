<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * Class GeoBounds.
 *
 * @property Fluent $topLeft;
 * @property Fluent $bottomRight;
 */
class GeoBounds extends Fluent
{
    /**
     * GeoBounds Aggregation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct([
            'topLeft' => new Fluent(Arr::get($attributes, 'bounds.top_left')),
            'bottomRight' => new Fluent(Arr::get($attributes, 'bounds.bottom_right')),
        ]);
    }
}
