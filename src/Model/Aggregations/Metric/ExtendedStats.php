<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class ExtendedStats.
 *
 * @property int $count;
 * @property int|float $min;
 * @property int|float $max;
 * @property int|float $avg;
 * @property int|float $sum;
 * @property int|float $sum_of_squares;
 * @property int|float $variance;
 * @property int|float $std_deviation;
 * @property Fluent $std_deviation_bounds;
 */
class ExtendedStats extends Fluent
{
    /**
     * ExtendedStats Aggregation constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $attributes['std_deviation_bounds'] = new Fluent($attributes['std_deviation_bounds']);

        parent::__construct($attributes);
    }
}
