<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AvgTest extends TestCase
{
    #[Test]
    public function it_builds_an_avg_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->avg('avg_grade', 'grade');
        $aggs->avg('avg_price', 'price', ['missing' => 10]);

        $this->assertEquals([
            'avg_grade' => ['avg' => ['field' => 'grade']],
            'avg_price' => ['avg' => ['field' => 'price', 'missing' => 10]],
        ], $aggs->toArray());
    }
}
