<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Regexp implements Arrayable
{
    public const KEY = 'regexp';

    /**
     * Field you wish to search for.
     *
     * @var string
     */
    private $field;

    /**
     * Regex expression.
     *
     * @var mixed
     */
    private $value;

    /**
     * Extra options given to elasticsearch.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-regexp-query.html#regexp-query-field-params
     *
     * @var array
     */
    private $options;

    /**
     * Regexp constructor.
     *
     * @param string $field
     * @param mixed  $value
     * @param array  $options
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
