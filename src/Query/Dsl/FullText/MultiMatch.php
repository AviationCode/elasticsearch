<?php

namespace AviationCode\Elasticsearch\Query\Dsl\FullText;

use Illuminate\Contracts\Support\Arrayable;

class MultiMatch implements Arrayable
{
    public const KEY = 'multi_match';

    /**
     * The fields to be queried.
     *
     * @var array
     */
    private $fields;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Extra options.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html
     *
     * @var array
     */
    private $options;

    /**
     * MatchPhrase constructor.
     *
     * @param array $fields
     * @param mixed $value
     * @param array $options
     */
    public function __construct(array $fields, $value, array $options = [])
    {
        $this->fields = $fields;
        $this->value = $value;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => array_merge([
                'query' => $this->value,
                'fields' => $this->fields,
            ], $this->options),
        ];
    }
}
