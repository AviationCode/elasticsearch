<?php

namespace AviationCode\Elasticsearch\Query\Dsl\FullText;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Match.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class Match implements Arrayable
{
    public const KEY = 'match';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * Value you wish to search.
     * Any text is analysed before performing a search.
     *
     * @var mixed
     */
    private $query;

    /**
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html#match-field-params
     *
     * @var array
     */
    private $options = [];

    /**
     * Match constructor.
     *
     * @param string $field
     * @param mixed  $query
     * @param array  $options
     */
    public function __construct(string $field, $query, array $options = [])
    {
        $this->field = $field;
        $this->query = $query;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            self::KEY => [
                $this->field => array_merge($this->options, ['query' => $this->query]),
            ],
        ];
    }
}
