<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Stats;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class StatsTest extends TestCase
{
    /** @test **/
    public function it_translates_stats_aggregation()
    {
        $value = [
            'count' => 2,
            'min' => 50.0,
            'max' => 100.0,
            'avg' => 75.0,
            'sum' => 150.0
        ];

        $stats = new Stats(['value' => $value]);

        $this->assertEquals($value, $stats->value());
    }
}
