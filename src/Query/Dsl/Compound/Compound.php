<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

use Illuminate\Contracts\Support\Arrayable;

abstract class Compound implements Arrayable
{
    /**
     * Key associated with this compound query.
     *
     * @var string
     */
    private $key;

    /**
     * Compound constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Get elastic compound query identifier.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
