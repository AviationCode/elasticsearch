<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Geo;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoShape;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeoShapeTest extends TestCase
{
    #[Test]
    public function it_builds_geo_shape_query(): void
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

    #[Test]
    public function it_builds_geo_shape_query_with_relation_intersects(): void
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::INTERSECTS);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'intersects',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_geo_shape_query_with_relation_disjoint(): void
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::DISJOINT);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'disjoint',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_geo_shape_query_with_relation_within(): void
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::WITHIN);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'within',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_geo_shape_query_with_relation_contains(): void
    {
        $geo = new GeoShape('location', ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]], GeoShape::CONTAINS);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'shape' => ['type' => 'envelope', 'coordinates' => [[13.0, 53.0], [14.0, 52.0]]],
                    'relation' => 'contains',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_geo_shape_query_with_indexed_shape(): void
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

    #[Test]
    public function it_builds_geo_shape_query_with_indexed_shape_ignore_unmapped(): void
    {
        $geo = new GeoShape('location', ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'], GeoShape::INDEXED_SHAPE, true);

        $this->assertEquals([
            'geo_shape' => [
                'location' => [
                    'indexed_shape' => ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'],
                    'ignored_unmapped' => true,
                ],
            ],
        ], $geo->toArray());
    }
}
