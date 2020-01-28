<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Min as MinModel;

class Min extends Metric
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('min', MinModel::class);

        $this->field = $field;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge(['field' => $this->field], $this->options);
    }
}
