<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Helpers\HasAttributes;

class BucketItem
{
    use HasAttributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }
}
