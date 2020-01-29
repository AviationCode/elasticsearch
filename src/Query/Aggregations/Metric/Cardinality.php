<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Cardinality as CardinalityModel;
use Illuminate\Support\Arr;

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

    /**
     * @var array
     */
    private $allowedOptions;

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('cardinality', CardinalityModel::class);

        $this->field = $field;

        $this->options = $options;

        $this->allowedOptions = ['precision_threshold', 'script', 'missing'];
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        $options = Arr::only($this->options, $this->allowedOptions);

        return array_merge([
            'field' => $this->field,
            'precision_threshold' => self::DEFAULT_PRECISION_THRESHOLD,
        ], $options);
    }
}
