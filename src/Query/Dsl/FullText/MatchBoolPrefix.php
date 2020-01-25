<?php

namespace AviationCode\Elasticsearch\Query\Dsl\FullText;

use Illuminate\Contracts\Support\Arrayable;

class MatchBoolPrefix implements Arrayable
{
    const KEY = 'match_bool_prefix';

    /**
     * The field to search for.
     *
     * @var string
     */
    private $field;

    /**
     * Value you want to search.
     *
     * @var mixed
     */
    private $value;

    /**
     * Extra options.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-bool-prefix-query.html#_parameters_3
     *
     * @var array
     */
    private $options;

    /**
     * MatchBoolPrefix constructor.
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
                $this->field => array_merge(['query' => $this->value], $this->options),
            ],
        ];
    }
}
