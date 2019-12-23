<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Compound;

class DisMax extends Compound
{
    /**
     * DisMax constructor.
     */
    public function __construct()
    {
        parent::__construct('dis_max');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [];
    }
}
