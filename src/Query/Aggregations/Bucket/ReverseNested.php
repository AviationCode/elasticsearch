<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class ReverseNested extends Bucket
{
    /**
     * @var string
     */
    private $path;

    /**
     * Nested constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct('reverse_nested');

        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return ['path' => $this->path];
    }
}
