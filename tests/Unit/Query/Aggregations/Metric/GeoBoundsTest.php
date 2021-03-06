<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoBoundsTest extends TestCase
{
    /** @test **/
    public function it_builds_a_geo_bounds_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->geoBounds('viewport', 'location', ['wrap_longitude' => true]);

        $this->assertEquals([
            'viewport' => ['geo_bounds' => ['field' => 'location', 'wrap_longitude' => true]],
        ], $aggs->toArray());
    }
}
