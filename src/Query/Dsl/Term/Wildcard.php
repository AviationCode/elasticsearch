<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Wildcard implements Arrayable
{
    const KEY = 'wildcard';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * Wildcard pattern for terms you wish to find in the provided field.
     *
     * @var mixed
     */
    private $value;

    /**
     * Extra search params.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-wildcard-query.html#wildcard-query-field-params
     *
     * @var array
     */
    private $options;

    /**
     * Wildcard constructor.
     *
     * @param string $field
     * @param mixed $value
     * @param array $options
     */
    public function __construct(string $field, $value, array $options = [])
    {
        $this->field = $field;
        $this->value = $value;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => [
                $this->field => array_merge(['value' => $this->value], $this->options),
            ],
        ];
    }
}
