<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BucketSort implements Arrayable
{
    use HasAggregations;

    public const SKIP = 'skip';
    public const INSERT_ZEROS = 'insert_zeros';

    /**
     * The list of fields to sort on.
     *
     * @var array
     */
    private $sort;

    /**
     * The policy to apply when gaps are found in the data.
     *
     * @var string|null
     */
    private $gap;

    /**
     * Buckets in positions prior to the set value will be truncated.
     *
     * @var int|null
     */
    private $from;

    /**
     * The number of buckets to return. Defaults to all buckets of the parent aggregation.
     *
     * @var int|null
     */
    private $size;

    /**
     * BucketSelector constructor.
     *
     * @param array|null $sort
     * @param int|null $size
     * @param int|null $from
     * @param string|null $gap
     */
    public function __construct(?array $sort = null, ?int $size = null, ?int $from = null, ?string $gap = null)
    {
        $this->key = 'bucket_sort';
        $this->aggregations = new Aggregation();

        if (is_array($sort)) {
            $sort = (new Collection($sort))
                ->map(function ($value, $key) {
                    return [$key => ['order' => $value]];
                })
                ->values()
                ->toArray();
        }

        $this->sort = $sort;
        $this->size = $size;
        $this->from = $from;
        $this->gap = $gap;
    }

    protected function toElastic()
    {
        $params = [];

        if ($this->sort) {
            $params['sort'] = $this->sort;
        }

        if ($this->from) {
            $params['from'] = $this->from;
        }

        if ($this->size) {
            $params['size'] = $this->size;
        }

        if ($this->gap) {
            $params['gap_policy'] = $this->gap;
        }

        return count($params) ? $params : new \stdClass();
    }
}
