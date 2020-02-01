<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use Illuminate\Support\Fluent;

/**
 * Class Sum.
 *
 * @property int|float $value;
 */
class Sum extends Fluent
{
    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
