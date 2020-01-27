<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Min as MinModel;

class Min extends Metric
{
    /**
     * @var string
     */
    protected $field;

    public function __construct(string $field)
    {
        parent::__construct('min', MinModel::class);

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
