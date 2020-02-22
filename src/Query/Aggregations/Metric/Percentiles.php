<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

class Percentiles extends Metric
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
        'tdigest',
        'hdr',
        'missing',
    ];

    /**
     * Percentiles constructor.
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('percentiles');

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
