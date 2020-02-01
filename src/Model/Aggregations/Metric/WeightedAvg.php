<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class WeightedAvg.
 *
 * @property int|float $value;
 */
class WeightedAvg extends Fluent
{
    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
