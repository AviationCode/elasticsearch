<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Cardinality as CardinalityModel;

class Cardinality extends Metric
{
    /**
     * @var string
     */
    private $field;

    public function __construct(string $field)
    {
        parent::__construct('cardinality', CardinalityModel::class);

        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return ['field' => $this->field];
    }
}
