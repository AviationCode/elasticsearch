<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Support\Collection;

abstract class Bucket extends Collection
{
    /**
     * Bucket constructor.
     *
     * @param array $value
     * @param HasAggregations $query
     */
    public function __construct(array $value, $query = null)
    {
        if (! $query) {
            return parent::__construct($value);
        }

        $nestedBuckets = $query->aggregations()->keys();

        parent::__construct(array_map(function ($item) use ($query, $nestedBuckets) {
            foreach ($nestedBuckets as $key) {
                $qb = $query->aggregations()->get($key);

                $item[$key] = $qb->newModel($item[$key], $qb);
            }

            return $this->newBucketItem($item);
        }, $value['buckets'] ?? []));
    }

    protected function newBucketItem($item): BucketItem
    {
        return new BucketItem($item);
    }
}
