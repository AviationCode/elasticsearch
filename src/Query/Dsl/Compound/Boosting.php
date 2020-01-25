<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

class Boosting extends Compound
{
    /**
     * Boosting constructor.
     */
    public function __construct()
    {
        parent::__construct('boosting');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
        return [];
    }
}
