<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Common;

use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

class Item extends Fluent
{
    public function __construct($attributes = [])
    {
        parent::__construct((new Collection($attributes))->mapWithKeys(function ($value, $key) {
            if (strpos($key, '#')) {
                return Aggregation::aggregationModel($key, $value);
            }

            return [$key => (is_array($value) && Arr::isAssoc($value)) ? new Item($value) : $value];
        }));
    }
}
