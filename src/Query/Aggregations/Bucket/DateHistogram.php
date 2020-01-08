<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\DateHistogram as DateHistogramModel;

class DateHistogram extends Bucket
{
    const CALENDAR = 'calendar_interval';
    const FIXED = 'fixed_interval';

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $interval;

    /**
     * @var string
     */
    private $intervalType;

    /**
     * @var array
     */
    private $options;

    /**
     * DateHistogram constructor.
     *
     * @param string $field
     * @param string $interval
     * @param string $intervalType
     * @param array $options
     */
    public function __construct(string $field, string $interval, string $intervalType = self::FIXED, array $options = [])
    {
        parent::__construct('date_histogram', DateHistogramModel::class);

        $this->field = $field;
        $this->interval = $interval;
        $this->intervalType = $intervalType;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge([
            'field' => $this->field,
            $this->intervalType => $this->interval,
        ], $this->options);
    }
}
