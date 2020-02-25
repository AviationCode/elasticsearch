<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use Illuminate\Contracts\Support\Arrayable;

class AdjacencyMatrix extends Bucket
{
    /**
     * @var array
     */
    private $filters = [];

    /**
     * AdjacencyMatrix constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        parent::__construct('adjacency_matrix');

        foreach ($filters as $key => $filter) {
            $this->filters[$key] = $filter instanceof Arrayable ? $filter->toArray() : $filter;
        }
    }

    /**
     * @param string $key
     * @param Arrayable|array $filter
     *
     * @return $this
     */
    public function add(string $key, $filter): self
    {
        $this->filters[$key] = $filter instanceof Arrayable ? $filter->toArray() : $filter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return ['filters' => $this->filters];
    }
}
