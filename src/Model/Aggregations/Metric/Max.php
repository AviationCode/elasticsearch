<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class Max.
 *
 * @property int|float $value;
 */
class Max extends Fluent
{
    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
