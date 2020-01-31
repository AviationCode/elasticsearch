<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoCentroidTest extends TestCase
{
    /** @test **/
    public function it_builds_a_geo_centroid_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->geoCentroid('centroid', 'location');

        $this->assertEquals([
            'centroid' => ['geo_centroid' => ['field' => 'location']],
        ], $aggs->toArray());
    }
}
