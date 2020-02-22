<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use Carbon\Carbon;

class AutoDateHistogram extends Bucket
{
    /**
     * {@inheritDoc}
     */
    protected function newBucketItem($item): BucketItem
    {
        $bucketItem = parent::newBucketItem($item);

        $bucketItem->date = Carbon::createFromTimestampMs($bucketItem->key);

        return $bucketItem;
    }
}
