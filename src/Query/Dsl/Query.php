<?php

namespace AviationCode\Elasticsearch\Query\Dsl;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Query\Dsl\Compound;
use Illuminate\Contracts\Support\Arrayable;

class Query implements Arrayable
{
    /**
     * @var Compound\Boolean
     */
    private $boolean;

    /**
     * @var Compound\Boosting
     */
    private $boosting;

    /**
     * @var Compound\ConstantScore
     */
    private $constantScore;

    /**
     * @var Compound\DisMax
     */
    private $disMax;

    /**
     * @var Compound\FunctionScore
     */
    private $functionScore;

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->boolean = new Compound\Boolean();
        $this->boosting = new Compound\Boosting();
        $this->constantScore = new Compound\ConstantScore();
        $this->disMax = new Compound\DisMax();
        $this->functionScore = new Compound\FunctionScore();
    }

    /**
     * Must clause must be contained inside compound boolean query when called from query.
     * The clause (query) must appear in matching documents and will contribute to the score.
     *
     * @param \Closure $callback
     *
     * @return Query
     */
    public function must(\Closure $callback): self
    {
        $this->boolean->must($callback);

        return $this;
    }


    /**
     * Filter clause (query) must appear in matching documents. However unlike must the score of
     * the query will be ignored. Filter clauses are executed in filter context, meaning that
     * scoring is ignored and clauses are considered for caching.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function filter(\Closure $callback): self
    {
        $this->boolean->filter($callback);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $query = array_filter([
            $this->boolean->getKey() => $this->boolean->toArray(),
            $this->boosting->getKey() => $this->boosting->toArray(),
            $this->constantScore->getKey() => $this->constantScore->toArray(),
            $this->disMax->getKey() => $this->disMax->toArray(),
            $this->functionScore->getKey() => $this->functionScore->toArray(),
        ]);

        // When no query is given assume we display everything by forcing boolean query to empty response.
        if (empty($query)) {
            return [$this->boolean->getKey() => []];
        }

        return $query;
    }
}
