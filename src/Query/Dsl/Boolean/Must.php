<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\FullText\Match;
use Illuminate\Contracts\Support\Arrayable;

class Must implements Arrayable
{
    const KEY = 'must';

    /**
     * List of must clauses to apply
     *
     * @var array
     */
    private $clauses = [];

    /**
     * Returns documents that match a provided text, number, date or boolean value.
     * The provided text is analyzed before matching.
     *
     * @param string $key
     * @param $query
     * @param array $options
     * @return $this
     */
    public function match(string $key, $query, array $options = []): self
    {
        $this->clauses[] = new Match($key, $query, $options);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return array_map(function ($clause) {
            return $clause->toArray();
        }, $this->clauses);
    }
}
