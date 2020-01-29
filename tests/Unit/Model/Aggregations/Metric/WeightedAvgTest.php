<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\WeightedAvg;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class WeightedAvgTest extends TestCase
{
    /** @test */
    public function it_translates_weighted_avg_aggregation()
    {
        $weightedAvg = new WeightedAvg(['value' => 2.0]);

        $this->assertEquals(2.0, $weightedAvg->value());
    }
}
