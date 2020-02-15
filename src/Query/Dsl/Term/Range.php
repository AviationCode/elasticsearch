<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Range implements Arrayable
{
    public const KEY = 'range';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html#range-query-field-params
     *
     * @var array
     */
    private $options = [];

    /**
     * gt(e) / lt(e) condition to apply.
     *
     * @var array
     */
    private $condition = [];

    /**
     * Range constructor.
     *
     * @param string     $field
     * @param string     $operator
     * @param mixed|null $value
     * @param array      $options
     */
    public function __construct(string $field, $operator = 'gte', $value = null, array $options = [])
    {
        $this->field = $field;
        $this->options = $options;

        // Allows you to build up a fluent range.
        if (is_callable($operator)) {
            $operator($this);
            $this->options = $value ?? $options;

            return;
        }

        if ($operator === 'gte' || $operator === '>=') {
            $this->gte($value);
        } elseif ($operator === 'gt' || $operator === '>') {
            $this->gt($value);
        } elseif ($operator === 'lte' || $operator === '<=') {
            $this->lte($value);
        } elseif ($operator === 'lt' || $operator === '<') {
            $this->lt($value);
        }
    }

    /**
     * Greater than or equal.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function gte($value): self
    {
        if (isset($this->condition['gt'])) {
            throw new \InvalidArgumentException('Cannot combine gte while gt condition is already applied');
        }

        $this->condition['gte'] = $value;

        return $this;
    }

    /**
     * Greater than or equal.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function gt($value): self
    {
        if (isset($this->condition['gte'])) {
            throw new \InvalidArgumentException('Cannot combine gt while gte condition is already applied');
        }

        $this->condition['gt'] = $value;

        return $this;
    }

    /**
     * Greater than or equal.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function lte($value): self
    {
        if (isset($this->condition['lt'])) {
            throw new \InvalidArgumentException('Cannot combine lte while lt condition is already applied');
        }

        $this->condition['lte'] = $value;

        return $this;
    }

    /**
     * Greater than or equal.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function lt($value): self
    {
        if (isset($this->condition['lte'])) {
            throw new \InvalidArgumentException('Cannot combine lt while lte condition is already applied');
        }

        $this->condition['lt'] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
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
