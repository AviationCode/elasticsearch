<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Ids implements Arrayable
{
    public const KEY = 'ids';

    /**
     * Array of document ids.
     *
     * @var array
     */
    private $values;

    /**
     * Ids constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::KEY => [
                'values' => $this->values,
            ],
        ];
    }
}
