<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Fuzzy implements Arrayable
{
    const KEY = 'fuzzy';

    const FUZZINESS_AUTO = 'AUTO';
    const REWRITE_CONSTANT = 'constant_score';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * Term wish to find.
     *
     * @var mixed
     */
    private $value;

    /**
     * extra options defined in.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html#fuzzy-query-field-params
     * @var array
     */
    private $options;

    /**
     * Fuzzy constructor.
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
