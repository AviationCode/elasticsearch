<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Missing extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * Missing constructor.
     *
     * @param string $field
     */
    public function __construct(string $field)
    {
        parent::__construct('missing');

        $this->field = $field;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return ['field' => $this->field];
    }
}
