<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

/**
 * Class GeoCentroid.
 *
 * @property array $value;
 */
class GeoCentroid implements \JsonSerializable
{
    use HasAttributes;

    /**
     * GeoCentroid Aggregation constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value['value'];
    }

    /**
     * Returns an array of fields
     * location (lat, lon) and count.
     *
     * @return array
     */
    public function value(): array
    {
        return $this->value;
    }
}
