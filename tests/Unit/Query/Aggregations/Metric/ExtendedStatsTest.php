<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use     AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ExtendedStatsTest extends TestCase
{
    #[Test]
    public function it_builds_an_extended_stats_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->extendedStats('prices_stats', 'price');
        $aggs->extendedStats('grades_stats', 'grade', ['sigma' => 3]);
        $aggs->extendedStats('quantities_stats', 'quantity', ['missing' => 1]);

        $this->assertEquals([
            'prices_stats' => ['extended_stats' => ['field' => 'price']],
            'grades_stats' => ['extended_stats' => ['field' => 'grade', 'sigma' => 3]],
            'quantities_stats' => ['extended_stats' => ['field' => 'quantity', 'missing' => 1]],
        ], $aggs->toArray());
    }
}
