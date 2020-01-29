<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Stats as StatsModel;

class Stats extends Metric
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
        parent::__construct('stats', StatsModel::class);

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
