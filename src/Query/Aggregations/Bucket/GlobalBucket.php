<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

class GlobalBucket implements Arrayable
{
    use HasAggregations;

    /**
     * GlobalBucket constructor.
     */
    public function __construct()
    {
        $this->key = 'global';
        $this->aggregations = new Aggregation();
    }

    /**
     * @inheritDoc
     */
    protected function toElastic()
    {
        return new \stdClass();
    }
}
