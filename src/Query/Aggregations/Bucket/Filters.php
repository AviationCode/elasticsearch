<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Filters extends Bucket
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @var string|null
     */
    private $otherKey;

    /**
     * Filters constructor.
     *
     * @param array $filters
     * @param string|null $otherKey
     */
    public function __construct(array $filters, ?string $otherKey = null)
    {
        parent::__construct('filters');

        $this->filters = $filters;
        $this->otherKey = $otherKey;

        if (count($this->filters) === 0) {
            throw new \InvalidArgumentException('Required at least 1 filter');
        }
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        $params = ['filters' => array_map(function ($filter) {
            return $filter->toArray();
        }, $this->filters)];

        if (! is_null($this->otherKey)) {
            $params['other_bucket_key'] = $this->otherKey;
        }

        return $params;
    }
}
