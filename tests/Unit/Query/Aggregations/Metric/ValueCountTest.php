<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ValueCountTest extends TestCase
{
    /** @test **/
    public function it_builds_a_value_count_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->valueCount('types_count', 'type');

        $this->assertEquals([
            'types_count' => ['value_count' => ['field' => 'type']],
        ], $aggs->toArray());
    }
}
