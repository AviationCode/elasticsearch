<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

class DiversifiedSampler implements Arrayable
{
    use HasAggregations;

    /**
     * @var int|null
     */
    private $shardSize;
    /**
     * @var string|null
     */
    private $field;
    /**
     * @var int|null
     */
    private $maxDocsPerValue;

    /**
     * Sampler constructor.
     *
     * @param int|null $shardSize
     * @param string|null $field
     * @param int|null $maxDocsPerValue
     */
    public function __construct(?int $shardSize = null, ?string $field = null, ?int $maxDocsPerValue = null)
    {
        $this->key = 'diversified_sampler';
        $this->aggregations = new Aggregation();

        $this->shardSize = $shardSize;
        $this->field = $field;
        $this->maxDocsPerValue = $maxDocsPerValue;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic()
    {
        $params = [];

        if ($this->shardSize) {
            $params['shard_size'] = $this->shardSize;
        }

        if ($this->field) {
            $params['field'] = $this->field;
        }

        if ($this->maxDocsPerValue) {
            $params['max_docs_per_value'] = $this->maxDocsPerValue;
        }

        return count($params) ? $params : new \stdClass();
    }
}
