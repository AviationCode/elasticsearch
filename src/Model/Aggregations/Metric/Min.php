<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class Min.
 *
 * @property int|float|float $value;
 */
class Min extends Fluent
{
    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
