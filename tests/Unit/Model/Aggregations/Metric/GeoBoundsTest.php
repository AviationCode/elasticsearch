<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\GeoBounds;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoBoundsTest extends TestCase
{
    /** @test */
    public function it_translates_geo_bounds_aggregation()
    {
        $value = [
            'bounds' => [
                'top_left' => [
                    'lat' => 48.86111099738628,
                    'lon' => 2.3269999679178,
                ],
                'bottom_right' => [
                    'lat' => 48.85999997612089,
                    'lon' => 2.3363889567553997,
                ],
            ],
        ];

        $geoBounds = new GeoBounds($value);

        $this->assertEquals(48.86111099738628, $geoBounds->topLeft->lat);
        $this->assertEquals(2.3269999679178, $geoBounds->topLeft->lon);
        $this->assertEquals(48.85999997612089, $geoBounds->bottomRight->lat);
        $this->assertEquals(2.3363889567553997, $geoBounds->bottomRight->lon);
    }
}
