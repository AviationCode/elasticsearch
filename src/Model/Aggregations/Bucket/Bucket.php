<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use Illuminate\Support\Collection;

abstract class Bucket extends Collection implements \JsonSerializable
{
    /**
     * Meta information attached to the bucket.
     *
     * @var array
     */
    protected $attributes = [];

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

    /**
     * Create a new bucket item.
     *
     * @param array $item
     * @return BucketItem
     */
    protected function newBucketItem($item): BucketItem
    {
        return new BucketItem($item);
    }

    /**
     * Get meta key out of the attributes array.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }

    /**
     * Set meta information into attributes array.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'meta' => $this->attributes,
            'data' => parent::jsonSerialize(),
        ];
    }
}
