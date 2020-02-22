<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter as FilterQuery;
use Illuminate\Support\Arr;

class Filter extends Bucket
{
    /** @var Filter */
    private $filter;

    public function __construct(callable $callback)
    {
        parent::__construct('filter');

        $this->filter = new FilterQuery();

        $callback($this->filter);

        if (count($this->filter) !== 1) {
            throw new \InvalidArgumentException('Required exactly 1 filter ' . $this->filter->count() . ' given');
        }
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return Arr::first($this->filter->toArray());
    }
}
