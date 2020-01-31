<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use Illuminate\Support\Fluent;

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
        $this->count = $value['count'];

        $this->min = $value['min'];

        $this->max = $value['max'];

        $this->avg = $value['avg'];

        $this->sum = $value['sum'];

        $this->sumOfSquares = $value['sum_of_squares'];

        $this->variance = $value['variance'];

        $this->stdDeviation = $value['std_deviation'];

        $this->stdDeviationBounds = new Fluent($value['std_deviation_bounds']);
    }
}
