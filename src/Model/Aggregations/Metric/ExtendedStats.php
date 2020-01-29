<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class ExtendedStats.
 *
 * @property array $value;
 */
class ExtendedStats implements \JsonSerializable
{
    use HasAttributes;

    /**
     * ExtendedStats Aggregation constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value['value'];
    }

    /**
     * Returns an array of numeric stats
     *
     * @return array
     */
    public function value(): array
    {
        return $this->value;
    }
}
