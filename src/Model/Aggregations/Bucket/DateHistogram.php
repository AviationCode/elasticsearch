<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use Carbon\Carbon;

class DateHistogram extends Bucket
{
    /**
     * {@inheritDoc}
     */
    protected function newBucketItem($item): BucketItem
    {
        $bucketItem = parent::newBucketItem($item);

        $bucketItem->date = Carbon::createFromTimestamp($bucketItem->key);

        return $bucketItem;
    }
}
