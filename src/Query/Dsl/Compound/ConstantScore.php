<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

class ConstantScore extends Compound
{
    /**
     * ConstantScore constructor.
     */
    public function __construct()
    {
        parent::__construct('constant_score');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [];
    }
}
