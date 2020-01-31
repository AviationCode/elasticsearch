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
        $this->count = $value['count'];

        $this->min = $value['min'];

        $this->max = $value['max'];

        $this->avg = $value['avg'];

        $this->sum = $value['sum'];
    }
}
