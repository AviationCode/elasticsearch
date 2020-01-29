<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Avg;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AvgTest extends TestCase
{
    /** @test */
    public function it_translated_avg_aggregation()
    {
        $avg = new Avg(['value' => 75.0]);

        $this->assertEquals(75.0, $avg->value());
    }
}