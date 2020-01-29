<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class Stats.
 *
 * @property array $value;
 */
class Stats implements \JsonSerializable
{
    use HasAttributes;

    /**
     * Stats Aggregation constructor.
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
