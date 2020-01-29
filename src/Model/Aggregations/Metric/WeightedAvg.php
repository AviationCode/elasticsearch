<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class WeightedAvg.
 *
 * @property int|float $value;
 */
class WeightedAvg implements \JsonSerializable
{
    use HasAttributes;

    /**
     * WeightedAvg Aggregation constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value['value'];
    }

    /**
     * @return float|int
     */
    public function value()
    {
        return $this->value;
    }
}
