<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\ElasticsearchServiceProvider;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var \AviationCode\Elasticsearch\FakeElasticsearch
     */
    protected $elastic;

    protected function getPackageProviders($app)
    {
        return [
            ElasticsearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->elastic = Elasticsearch::fake();
        Elasticsearch::enableEvents(false);
    }

    protected function markSuccessfull()
    {
        $this->assertTrue(true);
    }
}
