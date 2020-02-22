<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

class MedianAbsoluteDeviation extends Metric
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
        'script',
        'missing',
        'compression',
    ];

    /**
     * Percentiles constructor.
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('median_absolute_deviation');

        $this->field = $field;
        $this->options = $options;
    }


    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return array_merge(['field' => $this->field], $this->allowedOptions($this->options));
    }
}
