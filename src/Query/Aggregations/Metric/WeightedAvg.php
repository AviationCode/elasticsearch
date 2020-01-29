<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\WeightedAvg as WeightedAvgModel;

class WeightedAvg extends Metric
{
    /**
     * @var array
     */
    private $value;

    /**
     * @var array
     */
    private $weight;

    public function __construct(array $value, array $weight)
    {
        parent::__construct('weighted_avg', WeightedAvgModel::class);

        $this->value = $value;

        $this->weight = $weight;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return [
            'value' => $this->value,
            'weight' => $this->weight,
        ];
    }
}