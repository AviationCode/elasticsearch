<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;

class BucketSelector implements Arrayable
{
    use HasAggregations;

    public const SKIP = 'skip';
    public const INSERT_ZEROS = 'insert_zeros';

    /**
     * This path to the buckets we wish to perform bucket aggregation on.
     *
     * @var array|string
     */
    private $buckets;

    /**
     * The policy to apply when gaps are found in the data.
     *
     * @var string|null
     */
    private $gap;

    /**
     * A map of script variables and their associated path to the buckets we wish to use for the variable.
     *
     * @var string
     */
    private $script;

    /**
     * BucketSelector constructor.
     *
     * @param array $buckets
     * @param string $script
     * @param string|null $gap
     */
    public function __construct(array $buckets, string $script, ?string $gap = null)
    {
        $this->key = 'bucket_selector';
        $this->aggregations = new Aggregation();

        $this->buckets = $buckets;
        $this->script = $script;
        $this->gap = $gap;
    }

    protected function toElastic(): array
    {
        $params = [
            'buckets_path' => $this->buckets,
            'script' => $this->script,
        ];

        if ($this->gap) {
            $params['gap_policy'] = $this->gap;
        }

        return $params;
    }
}
