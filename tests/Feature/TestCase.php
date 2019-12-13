<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\ElasticsearchServiceProvider;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ElasticsearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        Elasticsearch::fake();
    }
}
