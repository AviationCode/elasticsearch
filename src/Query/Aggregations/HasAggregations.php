<?php

namespace AviationCode\Elasticsearch\Query\Aggregations;

use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Trait HasAggregations.
 *
 * @mixin Aggregation
 */
trait HasAggregations
{
    use ForwardsCalls;

    /**
     * @var Aggregation
     */
    protected $aggregations;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $params = [$this->key => $this->toElastic()];

        if ($this->aggregations->count()) {
            $params['aggs'] = $this->aggregations->toArray();
        }

        return $params;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->forwardCallTo($this->aggregations, $name, $arguments);
    }
}
