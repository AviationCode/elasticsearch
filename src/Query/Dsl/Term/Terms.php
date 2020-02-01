<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Terms implements Arrayable
{
    public const KEY = 'terms';

    /**
     * The field you wish to search for.
     *
     * @var string
     */
    private $field;

    /**
     * Terms to search for.
     *
     * @var array
     */
    private $values;

    /**
     * Optionally boost factor.
     *
     * @var float|null
     */
    private $boost;

    /**
     * Terms constructor.
     *
     * @param string $field
     * @param array $values
     * @param float|null $boost
     */
    public function __construct(string $field, array $values, ?float $boost = null)
    {
        $this->field = $field;
        $this->values = $values;
        $this->boost = $boost;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $params = [$this->field => $this->values];

        if ($this->boost) {
            $params['boost'] = $this->boost;
        }

        return [static::KEY => $params];
    }
}
