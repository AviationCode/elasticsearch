<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class ValueCount
 *
 * @property int|float|double $value;
 */
class ValueCount implements \JsonSerializable
{
    use HasAttributes;

    /**
     * ValueCount constructor.
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
