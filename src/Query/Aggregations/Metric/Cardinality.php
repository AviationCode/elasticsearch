<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Cardinality as CardinalityModel;

class Cardinality extends Metric
{
    const DEFAULT_PRECISION_THRESHOLD = 3000;

    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $options;

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('cardinality', CardinalityModel::class);

        $this->field = $field;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge([
            'field' => $this->field,
            'precision_threshold' => self::DEFAULT_PRECISION_THRESHOLD,
        ], $this->options);
    }
}
