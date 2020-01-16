<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

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
