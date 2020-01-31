<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class Sum.
 *
 * @property int|float $value;
 */
class Sum implements \JsonSerializable
{
    use HasAttributes;

    /**
     * Sum Aggregation constructor.
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
