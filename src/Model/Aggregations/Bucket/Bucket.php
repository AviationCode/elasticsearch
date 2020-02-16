<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Bucket;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;

abstract class Bucket implements \JsonSerializable, Jsonable, \ArrayAccess, \Countable
{
    use ForwardsCalls;

    /**
     * Meta information attached to the bucket.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * @var Collection
     */
    protected $items;

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

        $this->items = new Collection(array_map(function ($bucket) {
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
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->forwardCallTo($this->items, $method, $arguments);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Cannot set into a read only array.');
    }

    /**
     * @param mixed $offset
     *
     * @throws \LogicException
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('Cannot unset from read only array.');
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * @param int $options
     *
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'meta' => $this->attributes,
            'data' => $this->items->toArray(),
        ];
    }
}
