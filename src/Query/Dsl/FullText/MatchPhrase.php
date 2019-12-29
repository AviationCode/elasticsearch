<?php

namespace AviationCode\Elasticsearch\Query\Dsl\FullText;

use Illuminate\Contracts\Support\Arrayable;

class MatchPhrase implements Arrayable
{
    const KEY = 'match_phrase';

    /**
     * The field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Extra options.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query-phrase.html
     *
     * @var array
     */
    private $options;

    /**
     * MatchPhrase constructor.
     *
     * @param string $field
     * @param $value
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
