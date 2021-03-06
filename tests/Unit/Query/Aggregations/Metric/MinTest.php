<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MinTest extends TestCase
{
    /** @test **/
    public function it_builds_a_min_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->min('min_price', 'price');
        $aggs->min('min_grade', 'grade', ['missing' => 60]);

        $this->assertEquals([
            'min_price' => ['min' => ['field' => 'price']],
            'min_grade' => ['min' => ['field' => 'grade', 'missing' => 60]],
        ], $aggs->toArray());
    }
}
