<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Histogram extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var int
     */
    private $interval;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    protected $allowedOptions = [
        'offset',
        'order',
        'missing',
    ];

    /**
     * Histogram constructor.
     *
     * @param string $field
     * @param int $interval
     * @param array $options
     */
    public function __construct(string $field, int $interval, array $options = [])
    {
        parent::__construct('histogram');

        $this->field = $field;
        $this->interval = $interval;
        $this->options = $options;
    }


    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return array_merge([
            'field' => $this->field,
            'interval' => $this->interval,
        ], $this->allowedOptions($this->options));
    }
}
