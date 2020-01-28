<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Max as MaxModel;

class Max extends Metric
{
    /**
     * @var string
     */
    protected $field;

    public function __construct(string $field)
    {
        parent::__construct('max', MaxModel::class);

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
