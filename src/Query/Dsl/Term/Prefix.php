<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Prefix implements Arrayable
{
    const KEY = 'prefix';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * Beginning characters of terms you wish to find in the provided.
     *
     * @var mixed
     */
    private $value;

    /**
     * Method used to rewrite the query.
     *
     * @var string|null
     */
    private $rewrite;

    /**
     * Prefix constructor.
     *
     * @param string $field
     * @param mixed $value
     * @param string|null $rewrite
     */
    public function __construct(string $field, $value, ?string $rewrite = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->rewrite = $rewrite;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => [
                $this->field => array_merge(['value' => $this->value], array_filter(['rewrite' => $this->rewrite])),
            ],
        ];
    }
}
