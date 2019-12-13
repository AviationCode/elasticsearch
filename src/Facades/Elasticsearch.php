<?php

namespace AviationCode\Elasticsearch\Facades;

use AviationCode\Elasticsearch\FakeElasticsearch;
use Illuminate\Support\Facades\Facade;

/**
 * @see \AviationCode\Elasticsearch\Elasticsearch
 * @see FakeElasticsearch
 */
class Elasticsearch extends Facade
{
    public static function fake()
    {
        static::swap($fake = new FakeElasticsearch);

        return $fake;
    }

    protected static function getFacadeAccessor()
    {
        return 'elasticsearch';
    }
}
