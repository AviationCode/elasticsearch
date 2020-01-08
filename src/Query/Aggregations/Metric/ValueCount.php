<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Model\ValueCount as ValueCountModel;

class ValueCount extends Metric
{
    /**
     * @var string
     */
    private $field;

    public function __construct(string $field)
    {
        parent::__construct('value_count', ValueCountModel::class);

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
