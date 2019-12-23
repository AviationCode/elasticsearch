<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

class FunctionScore extends Compound
{
    /**
     * FunctionScore constructor.
     */
    public function __construct()
    {
        parent::__construct('function_score');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [];
    }
}
