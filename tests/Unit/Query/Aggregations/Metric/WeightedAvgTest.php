<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class WeightedAvgTest extends TestCase
{
    #[Test]
    public function it_builds_a_weighted_avg_aggregation(): void
    {
        $aggs = new Aggregation();

        $value = ['field' => 'price', 'missing' => 10];
        $weight = ['field' => 'sales_percentage', 'missing' => 0.10];
        $aggs->weightedAvg('weighted_price', $value, $weight);

        $this->assertEquals([
            'weighted_price' => ['weighted_avg' => ['value' => $value, 'weight' => $weight]],
        ], $aggs->toArray());
    }
}
