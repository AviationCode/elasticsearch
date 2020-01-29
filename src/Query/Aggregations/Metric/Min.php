<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Min as MinModel;
use Illuminate\Support\Arr;

class Min extends Metric
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
    protected $allowedOptions = ['missing'];

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
        return array_merge(['field' => $this->field], $this->allowedOptions($this->options));
    }
}
