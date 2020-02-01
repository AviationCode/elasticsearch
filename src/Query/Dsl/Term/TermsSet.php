<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class TermsSet implements Arrayable
{
    public const KEY = 'terms_set';

    /**
     * Field you wish to search for.
     *
     * @var string
     */
    private $field;

    /**
     * Values you wish to find.
     *
     * @var array
     */
    private $values;

    /**
     * Extra options.
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-set-query.html#terms-set-field-params
     *
     * @var array
     */
    private $options;

    /**
     * TermsSet constructor.
     * @param string $field
     * @param array $values
     * @param array $options
     */
    public function __construct(string $field, array $values, array $options = [])
    {
        $this->field = $field;
        $this->values = $values;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => [
                $this->field => array_merge(['terms' => $this->values], $this->options),
            ],
        ];
    }
}
