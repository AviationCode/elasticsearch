<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\ElasticsearchServiceProvider;

/**
 * Class ElasticsearchServiceProviderTest
 *
 * @package AviationCode\Elasticsearch\Tests\Feature
 */
#[CoversClass(\AviationCode\Elasticsearch\ElasticsearchServiceProvider::class)]
final class ElasticsearchServiceProviderTest extends TestCase
{
    #[Test]
    public function it_can_publish_the_configuration()
    {
        $configPath = base_path('config/elasticsearch.php');
        $this->artisan('vendor:publish', ['--provider' => ElasticsearchServiceProvider::class]);

        $this->assertTrue(file_exists($configPath));

        unlink($configPath);
    }
}