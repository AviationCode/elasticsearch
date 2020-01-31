<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use Illuminate\Support\Fluent;

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
        $this->location = new Fluent($value['location']);

        $this->count = $value['count'];
    }
}
