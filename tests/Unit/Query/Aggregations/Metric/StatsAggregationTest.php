<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class StatsAggregationTest extends TestCase
{
    /** @test **/
    public function it_builds_a_stats_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->stats('grades_stats', 'grade');
        $aggs->stats('quantities_stats', 'quantity', ['missing' => 1]);

        $this->assertEquals([
            'grades_stats'     => ['stats' => ['field' => 'grade']],
            'quantities_stats' => ['stats' => ['field' => 'quantity', 'missing' => 1]],
        ], $aggs->toArray());
    }
}
