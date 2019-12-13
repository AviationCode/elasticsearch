<?php

namespace AviationCode\Elasticsearch\Tests\Unit;

use AviationCode\Elasticsearch\Elasticsearch;

class ElasticsearchTest extends TestCase
{
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
