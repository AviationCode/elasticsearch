<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Children extends Bucket
{
    /**
     * @var string
     */
    private $type;

    /**
     * Children constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('children');

        $this->type = $type;
    }


    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return ['type' => $this->type];
    }
}
