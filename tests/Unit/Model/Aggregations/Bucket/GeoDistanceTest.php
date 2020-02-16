<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\GeoDistance;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoDistanceTest extends TestCase
{
    /** @test * */
    public function it_builds_geo_distance_response()
    {
        $geoDistance = new GeoDistance([
            'buckets' => [
                '*-100000.0' => [
                    "from" => 0.0,
                    "to" => 100000.0,
                    "doc_count" => 3
                ],
                "100000.0-300000.0" => [
                    "from" => 100000.0,
                    "to" => 300000.0,
                    "doc_count" => 1
                ],
                "300000.0-*" => [
                    "from" => 300000.0,
                    "doc_count" => 2
                ],
            ],
        ]);

        $this->assertCount(3, $geoDistance);
        $this->assertEquals(0.0, $geoDistance['*-100000.0']->from);
        $this->assertEquals(100000.0, $geoDistance['*-100000.0']->to);
        $this->assertEquals(3, $geoDistance['*-100000.0']->doc_count);

        $this->assertEquals(100000.0, $geoDistance['100000.0-300000.0']->from);
        $this->assertEquals(300000.0, $geoDistance['100000.0-300000.0']->to);
        $this->assertEquals(1, $geoDistance['100000.0-300000.0']->doc_count);

        $this->assertEquals(300000.0, $geoDistance['300000.0-*']->from);
        $this->assertNull($geoDistance['300000.0-*']->to);
        $this->assertEquals(2, $geoDistance['300000.0-*']->doc_count);
    }
}
