<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use Illuminate\Support\Collection;

abstract class Bucket extends Collection
{
    use HasAttributes;

    /**
     * Bucket constructor.
     *
     * @param array $aggregation
     */
    public function __construct(array $aggregation)
    {
        foreach ($aggregation as $key => $value) {
            if ($key !== 'buckets') {
                $this->$key = $value;

                continue;
            }
        }

        parent::__construct(array_map(function ($bucket) {
            return $this->newBucketItem($bucket);
        }, $aggregation['buckets']));
    }

    protected function newBucketItem($item): BucketItem
    {
        return new BucketItem($item);
    }
}
