<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoCentroidTest extends TestCase
{
    /** @test */
    public function it_translates_geo_centroid_aggregation()
    {
        $value = [
            'location' => [
                'lat' => 51.00982965203002,
                'lon' => 3.9662131341174245,
            ],
            'count' => 6,
        ];

        $geoCentroid = new Item($value);

        $this->assertEquals(6, $geoCentroid->count);
        $this->assertEquals(51.00982965203002, $geoCentroid->location->lat);
        $this->assertEquals(3.9662131341174245, $geoCentroid->location->lon);
    }
}
