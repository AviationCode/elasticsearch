<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use Carbon\Carbon;

class DateHistogram extends Bucket
{
    /**
     * {@inheritDoc}
     */
    protected function newItem($item): Item
    {
        $bucketItem = parent::newItem($item);

        $bucketItem->date = Carbon::createFromTimestampMs($bucketItem->key);

        return $bucketItem;
    }
}
