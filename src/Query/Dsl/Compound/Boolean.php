<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\MustNot;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Should;
use BadMethodCallException;

/**
 * Class Boolean
 *
 * @method $this must(\Closure $callback)
 * @method $this filter(\Closure $callback)
 * @method $this should(\Closure $callback)
 * @method $this mustNot(\Closure $callback)
 */
class Boolean extends Compound
{
    private $clauses = [];

    /**
     * Available boolean query drivers.
     *
     * @var array
     */
    private $drivers = [
        'must' => Must::class,
        'filter' => Filter::class,
        'should' => Should::class,
        'mustNot' => MustNot::class,
    ];

    /**
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-bool-query.html#bool-min-should-match
     *
     * @var null|int|float
     */
    private $minimumShouldMatch = null;

    /**
     * Bost this boolean query result by given factor.
     *
     * @var null|float
     */
    private $boost = null;

    /**
     * Boolean constructor.
     */
    public function __construct()
    {
        parent::__construct('bool');
    }

    /**
     * Add a new clause.
     *
     * @param string $method
     * @param \Closure $callback
     *
     * @return $this
     */
    protected function query(string $method, \Closure $callback): self
    {
        $class = $this->drivers[$method];

        if (! isset($this->clauses[$class::KEY])) {
            $this->clauses[$class::KEY] = new $class;
        }

        $callback($this->clauses[$class::KEY]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $arguments)
    {
        if (isset($this->drivers[$method]) && isset($arguments[0])) {
            return $this->query($method, $arguments[0]);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
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
