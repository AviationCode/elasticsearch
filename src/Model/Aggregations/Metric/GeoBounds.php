<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class GeoBounds.
 *
 * @property array $value;
 */
class GeoBounds implements \JsonSerializable
{
    use HasAttributes;

    /**
     * GeoBounds Aggregation constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value['value'];
    }

    /**
     * Returns an array of bounds
     * with lat, lon fields.
     *
     * @return array
     */
    public function value(): array
    {
        return $this->value;
    }
}
