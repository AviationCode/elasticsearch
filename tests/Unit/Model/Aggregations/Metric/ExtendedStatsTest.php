<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\ExtendedStats;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ExtendedStatsTest extends TestCase
{
    /** @test **/
    public function it_translates_extended_stats_aggregation()
    {
        $value = [
            'count' => 2,
            'min' => 50.0,
            'max' => 100.0,
            'avg' => 75.0,
            'sum' => 150.0,
            'sum_of_squares' => 12500.0,
            'variance' => 625.0,
            'std_deviation' => 25.0,
            'std_deviation_bounds' => [
                'upper' => 125.0,
                'lower' => 25.0
            ],
        ];

        $extendedStats = new ExtendedStats(['value' => $value]);

        $this->assertEquals($value, $extendedStats->value());
    }
}
