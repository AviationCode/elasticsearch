<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Range implements Arrayable
{
    const KEY = 'range';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html#range-query-field-params
     * @var array
     */
    private $options = [];

    /**
     * gt(e) / lt(e) condition to apply.
     *
     * @var array
     */
    private $condition = [];

    public function __construct($field, $operator = 'gte', $date = null, array $options = [])
    {
        $this->field = $field;
        $this->options = $options;

        // Allows you to build up a fluent range.
        if (is_callable($operator)) {
            $operator($this);

            return;
        }

        if ($operator === 'gte' || $operator === '>=') {
            $this->gte($date);
        } else if ($operator === 'gt' || $operator === '>') {
            $this->gt($date);
        } else if ($operator === 'lte' || $operator === '<=') {
            $this->lte($date);
        } else if ($operator === 'lt' || $operator === '<') {
            $this->lt($date);
        }
    }

    /**
     * @param $date
     *
     * @return $this
     */
    public function gte($date): self
    {
        $this->condition['gte'] = $date;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            self::KEY => [
                $this->field => array_merge($this->options, $this->condition),
            ],
        ];
    }
}
