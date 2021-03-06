<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class WeightedAvgTest extends TestCase
{
    /** @test **/
    public function it_builds_a_weighted_avg_aggregation()
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
