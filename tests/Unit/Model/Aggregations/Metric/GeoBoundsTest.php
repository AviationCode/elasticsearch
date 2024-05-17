<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Metric\GeoBounds;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoBoundsTest extends TestCase
{
    #[Test]
    public function it_translates_geo_bounds_aggregation(): void
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

        $this->assertEquals(48.86111099738628, $geoBounds->top_left->lat);
        $this->assertEquals(2.3269999679178, $geoBounds->top_left->lon);
        $this->assertEquals(48.85999997612089, $geoBounds->bottom_right->lat);
        $this->assertEquals(2.3363889567553997, $geoBounds->bottom_right->lon);
    }
}
