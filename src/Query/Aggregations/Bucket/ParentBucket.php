<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class ParentBucket extends Bucket
{
    /**
     * @var string
     */
    private $type;

    /**
     * ParentBucket constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct('parent');

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
