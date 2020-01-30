<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class Avg.
 *
 * @property int|float $value;
 */
class Avg implements \JsonSerializable
{
    use HasAttributes;

    /**
     * Avg Aggregation constructor.
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
