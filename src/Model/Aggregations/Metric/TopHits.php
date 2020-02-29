<?php

namespace AviationCode\Elasticsearch\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Model\ElasticHit;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Class TopHits.
 *
 * @mixin ElasticCollection
 */
class TopHits implements \ArrayAccess
{
    use ForwardsCalls;

    /**
     * @var ElasticCollection
     */
    private $hits;

    /**
     * TopHits constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->hits = ElasticCollection::parse($attributes, new ElasticHit());
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->hits, $method, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return $this->hits->offsetExists($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->hits->offsetGet($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Unable modify to read only collection');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('Unable modify to read only collection');
    }
}
