<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Filters as FiltersModel;

class Filters extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $options;

    protected $allowedOptions = ['other_bucket', 'other_bucket_key'];

    public function __construct(array $filters, array $options = [])
    {
        parent::__construct('filters', FiltersModel::class);

        $this->filters = $filters;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge([
            'filters' => $this->filters,
        ], $this->allowedOptions($this->options));
    }
}
