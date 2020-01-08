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
    public function __construct(array $value, $query)
    {
        $nestedBuckets = $query->aggregations()->keys();

        parent::__construct(array_map(function ($item) use ($query, $nestedBuckets) {
            foreach ($nestedBuckets as $key) {
                $qb = $query->aggregations()->get($key);

                $item[$key] = $qb->newModel($item[$key], $qb, $key);
            }

            return new BucketItem($item);
        }, $value['buckets'] ?? []));
    }
}
