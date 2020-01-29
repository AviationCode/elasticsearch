<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Max as MaxModel;
use Illuminate\Support\Arr;

class Max extends Metric
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
    private $allowedOptions;

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('max', MaxModel::class);

        $this->field = $field;

        $this->options = $options;

        $this->allowedOptions = ['script', 'missing'];
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        $options = Arr::only($this->options, $this->allowedOptions);

        return array_merge(['field' => $this->field], $options);
    }
}