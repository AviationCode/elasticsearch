<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Model;

use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;

class ValueCount extends Aggregation
{
    /**
     * @var int|float
     */
    protected $value;

    public function __construct(array $value, $query, ?string $key = null)
    {
        parent::__construct($value, $query, $key);

        $this->value = $value['value'];
    }

    public function value()
    {
        return $this->value;
    }
}
