<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\ElasticsearchServiceProvider;

/**
 * Class ElasticsearchServiceProviderTest
 *
 * @package AviationCode\Elasticsearch\Tests\Feature
 *
 * @covers \AviationCode\Elasticsearch\ElasticsearchServiceProvider
 */
final class ElasticsearchServiceProviderTest extends TestCase
{
    /** @test */
    public function it_can_publish_the_configuration()
    {
        $configPath = base_path('config/elasticsearch.php');
        $this->artisan('vendor:publish', ['--provider' => ElasticsearchServiceProvider::class]);

        $this->assertTrue(file_exists($configPath));

        unlink($configPath);
    }
}