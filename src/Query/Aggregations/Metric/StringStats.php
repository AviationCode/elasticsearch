<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

class StringStats extends Metric
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
    protected $allowedOptions = [
        'show_distribution',
        'script',
        'missing',
    ];

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('string_stats');

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
