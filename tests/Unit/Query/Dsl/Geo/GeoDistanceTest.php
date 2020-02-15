<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Geo;

use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoDistance;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoDistanceTest extends TestCase
{
    /** @test **/
    public function it_builds_geo_distance_query()
    {
        $geo = new GeoDistance('pin.location', 40, -70, 200, GeoDistance::KM);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200km',
                'pin.location' => [
                    'lat' => 40,
                    'lon' => -70,
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_in_other_units()
    {
        $geo = new GeoDistance('pin.location', 40, -70, 200, GeoDistance::M);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200m',
                'pin.location' => [
                    'lat' => 40,
                    'lon' => -70,
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_has_all_distance_units()
    {
        $this->assertEquals('mi', GeoDistance::MI);
        $this->assertEquals('yd', GeoDistance::YD);
        $this->assertEquals('ft', GeoDistance::FT);
        $this->assertEquals('in', GeoDistance::IN);
        $this->assertEquals('km', GeoDistance::KM);
        $this->assertEquals('m', GeoDistance::M);
        $this->assertEquals('cm', GeoDistance::CM);
        $this->assertEquals('mm', GeoDistance::MM);
        $this->assertEquals('NM', GeoDistance::NM);
    }

    /** @test **/
    public function it_builds_geo_distance_query_geo_json()
    {
        $geo = new GeoDistance('pin.location', [-70, 40], 200, GeoDistance::KM);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200km',
                'pin.location' => [-70, 40],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_location_as_string()
    {
        $geo = new GeoDistance('pin.location', '40, -70', 200, GeoDistance::KM);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200km',
                'pin.location' => '40, -70',
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_geohash()
    {
        $geo = new GeoDistance('pin.location', 'drm3btev3e86', 200, GeoDistance::KM);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200km',
                'pin.location' => 'drm3btev3e86',
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_json()
    {
        $geo = new GeoDistance('pin.location', ['lat' => 40, 'lon' => -70], 200, GeoDistance::KM);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => '200km',
                'pin.location' => ['lat' => 40, 'lon' => -70],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_minimal()
    {
        $geo = new GeoDistance('pin.location', '40, -70', 100);

        $this->assertEquals([
            'geo_distance' => [
                'distance'     => 100,
                'pin.location' => '40, -70',
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_minimal_with_options()
    {
        $geo = new GeoDistance('pin.location', '40, -70', 100, ['distance_type' => GeoDistance::ARC]);

        $this->assertEquals([
            'geo_distance' => [
                'distance'      => 100,
                'pin.location'  => '40, -70',
                'distance_type' => 'arc',
            ],
        ], $geo->toArray());

        $geo = new GeoDistance('pin.location', 40, -70, 100, ['distance_type' => GeoDistance::ARC]);

        $this->assertEquals([
            'geo_distance' => [
                'distance'      => 100,
                'pin.location'  => ['lat' => 40, 'lon' => -70],
                'distance_type' => 'arc',
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_distance_query_with_options()
    {
        $geo = new GeoDistance('pin.location', '40, -70', 200, GeoDistance::KM, [
            'distance_type'     => GeoDistance::PLANE,
            'validation_method' => GeoDistance::IGNORE_MALFORMED,
            'ignore_unmapped'   => true,
        ]);

        $this->assertEquals([
            'geo_distance' => [
                'distance'          => '200km',
                'pin.location'      => '40, -70',
                'distance_type'     => 'plane',
                'validation_method' => 'ignore_malformed',
                'ignore_unmapped'   => true,
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_throws_invalid_argument_exception_when_distance_is_not_provided()
    {
        $this->expectException(\InvalidArgumentException::class);

        new GeoDistance('pin.location', 40, -70);

        $this->markSuccessfull();
    }
}
