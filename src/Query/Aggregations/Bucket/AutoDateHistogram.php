<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class AutoDateHistogram extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    protected $allowedOptions = [
        'buckets',
        'format',
        'time_zone',
        'minimum_interval',
        'missing',
    ];

    public function __construct(string $field, array $options = [])
    {
        parent::__construct('auto_date_histogram');

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
