<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Cardinality as CardinalityModel;
use Illuminate\Support\Arr;

class Cardinality extends Metric
{
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
    protected $allowedOptions = ['precision_threshold', 'missing'];

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
        return array_merge(['field' => $this->field], $this->allowedOptions($this->options));
    }
}
