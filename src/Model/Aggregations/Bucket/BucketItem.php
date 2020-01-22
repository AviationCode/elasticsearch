<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Helpers\HasAttributes;
use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;

class BucketItem implements \JsonSerializable
{
    use HasAttributes;

    public function __construct($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (strpos($key, '#')) {
                [$key, $instance] = Aggregation::aggregationModel($key, $value);

                $this->$key = $instance;

                continue;
            }

            $this->$key = $value;
        }
    }
}
