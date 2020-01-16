<?php

namespace AviationCode\Elasticsearch\Facades;

use AviationCode\Elasticsearch\FakeElasticsearch;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Query\Builder;
use AviationCode\Elasticsearch\Schema\Index;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \AviationCode\Elasticsearch\Elasticsearch forModel($model)
 * @method static bool add($model)
 * @method static bool update($model)
 * @method static ElasticCollection bulk(Collection $models)
 * @method static Index index()
 * @method static Builder query($model = null)
 *
 * @mixin  \AviationCode\Elasticsearch\Elasticsearch
 * @mixin  FakeElasticsearch
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
