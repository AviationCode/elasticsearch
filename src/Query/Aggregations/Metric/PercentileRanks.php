<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

class PercentileRanks extends Metric
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $values;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    protected $allowedOptions = [
        'script',
        'hdr',
        'missing',
    ];

    /**
     * Percentiles constructor.
     * @param string $field
     * @param array $values
     * @param array $options
     */
    public function __construct(string $field, array $values, array $options = [])
    {
        parent::__construct('percentile_ranks');

        $this->field = $field;
        $this->options = $options;
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return array_merge([
            'field' => $this->field,
            'values' => $this->values,
        ], $this->allowedOptions($this->options));
    }
}
