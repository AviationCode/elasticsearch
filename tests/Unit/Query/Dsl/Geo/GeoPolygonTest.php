<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Geo;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoPolygon;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoPolygonTest extends TestCase
{
    #[Test]
    public function it_builds_a_geo_polygon()
    {
        $geo = new GeoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ]);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        ['lat' => 51.1, 'lon' => 4.1],
                        ['lat' => 52.2, 'lon' => 5.2],
                        ['lat' => 53.3, 'lon' => 6.3],
                    ],
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_lon_lat_array()
    {
        $geo = new GeoPolygon('location', [
            [-70, 40],
            [-80, 30],
            [-90, 20],
        ]);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        [-70, 40],
                        [-80, 30],
                        [-90, 20],
                    ],
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_lat_lon_string()
    {
        $geo = new GeoPolygon('location', [
            '40, -70',
            '30, -80',
            '20, -90',
        ]);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        '40, -70',
                        '30, -80',
                        '20, -90',
                    ],
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_geohash()
    {
        $geo = new GeoPolygon('location', [
            'drn5x1g8cu2y',
            '30, -80',
            '20, -90',
        ]);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        'drn5x1g8cu2y',
                        '30, -80',
                        '20, -90',
                    ],
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_validation_method()
    {
        $geo = new GeoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ], GeoPolygon::IGNORE_MALFORMED);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        ['lat' => 51.1, 'lon' => 4.1],
                        ['lat' => 52.2, 'lon' => 5.2],
                        ['lat' => 53.3, 'lon' => 6.3],
                    ],
                    'validation_method' => 'ignore_malformed',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_validation_method_coerce()
    {
        $geo = new GeoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ], GeoPolygon::COERCE);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        ['lat' => 51.1, 'lon' => 4.1],
                        ['lat' => 52.2, 'lon' => 5.2],
                        ['lat' => 53.3, 'lon' => 6.3],
                    ],
                    'validation_method' => 'coerce',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_validation_method_strict()
    {
        $geo = new GeoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ], GeoPolygon::STRICT);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        ['lat' => 51.1, 'lon' => 4.1],
                        ['lat' => 52.2, 'lon' => 5.2],
                        ['lat' => 53.3, 'lon' => 6.3],
                    ],
                    'validation_method' => 'strict',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_polygon_ignore_unmapped()
    {
        $geo = new GeoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ], GeoPolygon::STRICT, true);

        $this->assertEquals([
            'geo_polygon' => [
                'location' => [
                    'points' => [
                        ['lat' => 51.1, 'lon' => 4.1],
                        ['lat' => 52.2, 'lon' => 5.2],
                        ['lat' => 53.3, 'lon' => 6.3],
                    ],
                    'validation_method' => 'strict',
                    'ignore_unmapped' => true,
                ],
            ],
        ], $geo->toArray());
    }
}
