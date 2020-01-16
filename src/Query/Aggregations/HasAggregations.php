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
     * @return Aggregation
     */
    public function aggregations(): Aggregation
    {
        return $this->aggregations;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $params = [$this->key => $this->toElastic()];

        if (count($this->aggregations)) {
            $params['aggs'] = $this->aggregations->toArray();
        }

        return $params;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $arguments)
    {
        return $this->forwardCallTo($this->aggregations, $name, $arguments);
    }
}
