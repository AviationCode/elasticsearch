<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;

class Boolean extends Compound
{
    /**
     * @var Must
     */
    private $must;

    private $clauses = [];

    /**
     * Boolean constructor.
     */
    public function __construct()
    {
        parent::__construct('bool');
    }

    /**
     * Build up a must clause.
     *
     * @param \Closure $callback
     *
     * @return bool
     */
    public function must(\Closure $callback): self
    {
        if (! isset($this->clauses[Must::KEY])) {
            $this->clauses[Must::KEY] = new Must();
        }

        $callback($this->clauses[Must::KEY]);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return bool
     */
    public function filter(\Closure $callback): self
    {
        if (! isset($this->clauses[Filter::KEY])) {
            $this->clauses[Filter::KEY] = new Filter();
        }

        $callback($this->clauses[Filter::KEY]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_map(function ($clause) {
            return $clause->toArray();
        }, $this->clauses);
    }
}
