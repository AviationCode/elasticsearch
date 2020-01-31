<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class DateRange extends Bucket
{
    protected function newBucketItem($item): BucketItem
    {
        $bucketItem = parent::newBucketItem($item);

        /**
         * Use Carbon to map/parse timestamp(s) of milliseconds
         * stored in 'to', 'from' fields within bucket items.
         */
        foreach (Arr::only($bucketItem->toArray(), ['from', 'to']) as $key => $timestampInMillis) {
            $bucketItem->{$key} = Carbon::createFromTimestampMs($timestampInMillis);
        }

        return $bucketItem;
    }
}
