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
 * @method static ElasticCollection bulk($models, $data = null, $key = 'id')
 * @method static Index index()
 * @method static Builder query($model = null)
 *
 * @mixin  \AviationCode\Elasticsearch\Elasticsearch
 * @mixin  FakeElasticsearch
 */
class Elasticsearch extends Facade
{
    /**
     * @return FakeElasticsearch
     */
    public static function fake(): FakeElasticsearch
    {
        static::swap($fake = new FakeElasticsearch(static::$app));

        return $fake;
    }

    protected static function getFacadeAccessor()
    {
        return 'elasticsearch';
    }
}
