<?php

namespace AviationCode\Elasticsearch\Query\Dsl;

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
     * The clause (query) should appear in the matching document.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function should(\Closure $callback): self
    {
        $this->boolean->should($callback);

        return $this;
    }

    /**
     * The clause (query) must not appear in the matching documents.
     * Clauses are executed in filter context meaning that scoring is ignored and clauses are considered for caching.
     * Because scoring is ignored, a score of 0 for all documents is returned.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function mustNot(\Closure $callback): self
    {
        $this->boolean->mustNot($callback);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return collect([
            $this->boolean->toArray(),
            $this->boosting->toArray(),
            $this->constantScore->toArray(),
            $this->disMax->toArray(),
            $this->functionScore->toArray(),
        ])->mapWithKeys(function ($value, $key) {
            if (! $value) {
                return [$key => $value];
            }

            $key = array_key_first($value);

            return [$key => $value[$key]];
        })->filter()->toArray();
    }


    /**
     * @return void
     */
    public function __clone()
    {
        $this->boolean = clone $this->boolean;
        $this->boosting = clone $this->boosting;
        $this->constantScore = clone $this->constantScore;
        $this->disMax = clone $this->disMax;
        $this->functionScore = clone $this->functionScore;
    }
}
