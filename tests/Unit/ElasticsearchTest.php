<?php

namespace AviationCode\Elasticsearch\Tests\Unit;

use AviationCode\Elasticsearch\Elasticsearch;
use Elasticsearch\Client;

class ElasticsearchTest extends TestCase
{
    /** @var Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface Moc */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        Elasticsearch::enableEvents(false);
        $this->client = \Mockery::mock(Client::class);
    }

    /** @test **/
    public function it_can_enable_firing_events()
    {
        $this->assertFalse(Elasticsearch::shouldSentEvents());

        Elasticsearch::enableEvents();

        $this->assertTrue(Elasticsearch::shouldSentEvents());

        Elasticsearch::enableEvents(false);

        $this->assertFalse(Elasticsearch::shouldSentEvents());
    }
}
