<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class StatTest extends TestCase
{
    #[Test]
    public function it_builds_a_stats_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->stats('grades_stats', 'grade');
        $aggs->stats('quantities_stats', 'quantity', ['missing' => 1]);

        $this->assertEquals([
            'grades_stats' => ['stats' => ['field' => 'grade']],
            'quantities_stats' => ['stats' => ['field' => 'quantity', 'missing' => 1]],
        ], $aggs->toArray());
    }
}
