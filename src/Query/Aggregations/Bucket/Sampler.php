<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

class Sampler implements Arrayable
{
    use HasAggregations;

    /**
     * @var int|null
     */
    private $shardSize;

    /**
     * Sampler constructor.
     *
     * @param int|null $shardSize
     */
    public function __construct(?int $shardSize = null)
    {
        $this->key = 'sampler';
        $this->aggregations = new Aggregation();

        $this->shardSize = $shardSize;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic()
    {
        if ($this->shardSize) {
            return ['shard_size' => $this->shardSize];
        }

        return new \stdClass();
    }
}
