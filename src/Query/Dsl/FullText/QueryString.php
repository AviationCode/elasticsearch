<?php

namespace AviationCode\Elasticsearch\Query\Dsl\FullText;

use Illuminate\Contracts\Support\Arrayable;

class QueryString implements Arrayable
{
    const KEY = 'query_string';

    /**
     * Query string you wish to parse and use for search.
     *
     * @var mixed
     */
    private $value;

    /**
     * Extra options.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html#query-string-top-level-params
     *
     * @var array
     */
    private $options;

    /**
     * QueryString constructor.
     *
     * @param mixed $value
     * @param array $options
     */
    public function __construct($value, array $options = [])
    {
        $this->value = $value;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => array_merge(['query' => $this->value], $this->options),
        ];
    }
}
