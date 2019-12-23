<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Term\Range;
use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use Illuminate\Contracts\Support\Arrayable;

class Filter implements Arrayable
{
    const KEY = 'filter';

    /**
     * Filter clauses to apply.
     *
     * @var array
     */
    private $clauses = [];

    /**
     * Returns documents that contain an exact term in a provided field.
     *
     * @param string $field
     * @param string $value
     * @param float|null $boost
     *
     * @return $this
     */
    public function term(string $field, string $value, ?float $boost = null): self
    {
        $this->clauses[] = new Term($field, $value, $boost);

        return $this;
    }

    /**
     * Filter a date range.
     *
     * @param $field
     * @param string $operator
     * @param null $date
     * @param array $options
     *
     * @return $this
     */
    public function range($field, $operator = 'gte', $date = null, array $options = []): self
    {
        $this->clauses[] = new Range($field, $operator, $date, $options);

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
