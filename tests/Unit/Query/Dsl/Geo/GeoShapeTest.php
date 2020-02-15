<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Geo;

use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoShape;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoShapeTest extends TestCase
{
    /** @test **/
    public function it_builds_geo_shape_query()
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]]);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_relation_intersects()
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::INTERSECTS);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape'    => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'intersects',
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_relation_disjoint()
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::DISJOINT);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape'    => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'disjoint',
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_relation_within()
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::WITHIN);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape'    => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'within',
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_relation_contains()
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::CONTAINS);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape'    => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'contains',
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_indexed_shape()
    {
        $geo = new GeoShape('location', ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'], GeoShape::INDEXED_SHAPE);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'indexed_shape' => ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'],
                ],
            ],
        ], $geo->toArray());
    }

    /** @test **/
    public function it_builds_geo_shape_query_with_indexed_shape_ignore_unmapped()
    {
        $geo = new GeoShape('location', ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'], GeoShape::INDEXED_SHAPE, true);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'indexed_shape'    => ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'],
                    'ignored_unmapped' => true,
                ],
            ],
        ], $geo->toArray());
    }
}
