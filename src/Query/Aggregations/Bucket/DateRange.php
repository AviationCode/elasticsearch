<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\DateRange as DateRangeModel;

class DateRange extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $ranges;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    protected $allowedOptions = ['format', 'time_zone', 'keyed'];

    public function __construct(string $field, array $ranges, array $options = [])
    {
        parent::__construct('date_range', DateRangeModel::class);

        $this->field = $field;

        $this->ranges = $ranges;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge([
            'field'  => $this->field,
            'ranges' => $this->ranges,
        ], $this->allowedOptions($this->options));
    }
}
