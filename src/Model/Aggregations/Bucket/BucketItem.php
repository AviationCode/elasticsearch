<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class BucketItem extends Fluent
{
    public function __construct($attributes = [])
    {
        parent::__construct((new Collection($attributes))->mapWithKeys(function ($value, $key) {
            if (strpos($key, '#')) {
                return Aggregation::aggregationModel($key, $value);
            }

            return [$key => $value];
        }));
    }
}
