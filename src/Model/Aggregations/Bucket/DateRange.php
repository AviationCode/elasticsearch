<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class DateRange extends Bucket
{
    /**
     * {@inheritDoc}
     */
    protected function newItem($item): Item
    {
        $bucketItem = parent::newItem($item);

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
