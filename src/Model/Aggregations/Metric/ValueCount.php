<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;

class ValueCount
{
    /**
     * @var int|float
     */
    protected $value;

    /**
     * ValueCount constructor.
     *
     * @param array $value
     * @param HasAggregations $query
     */
    public function __construct(array $value, $query)
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
