<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class ValueCount.
 *
 * @property int|float|float $value;
 */
class ValueCount extends Fluent
{
    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
