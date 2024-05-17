<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Geo;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoBoundingBox;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class GeoBoundingBoxTest extends TestCase
{
    #[Test]
    public function it_builds_a_geo_bounding_box_query(): void
    {
        $geo = new GeoBoundingBox('location', ['lat' => 40.73, 'lon' => -74.1], ['lat' => 40.01, 'lon' => -71.12], [
            'validation_method' => GeoboundingBox::IGNORE_MALFORMED,
            'type' => GeoBoundingBox::MEMORY,
            'ignore_unmapped' => true,
        ]);

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'top_left' => ['lat' => 40.73, 'lon' => -74.1],
                    'bottom_right' => ['lat' => 40.01, 'lon' => -71.12],
                ],
                'validation_method' => 'ignore_malformed',
                'type' => 'memory',
                'ignore_unmapped' => true,
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_bounding_box_query_as_array(): void
    {
        $geo = new GeoBoundingBox('location', [-74.1, 40.73], [-71.12, 40.01], [
            'validation_method' => GeoboundingBox::STRICT,
            'type' => GeoBoundingBox::INDEXED,
        ]);

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'top_left' => [-74.1, 40.73],
                    'bottom_right' => [-71.12, 40.01],
                ],
                'validation_method' => 'strict',
                'type' => 'indexed',
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_bounding_box_query_as_string(): void
    {
        $geo = new GeoBoundingBox('location', '40.73, -74.1', '40.01, -71.12');

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'top_left' => '40.73, -74.1',
                    'bottom_right' => '40.01, -71.12',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_bounding_box_query_as_geo_hash(): void
    {
        $geo = new GeoBoundingBox('location', 'dr5r9ydj2y73', 'drj7teegpus6');

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'top_left' => 'dr5r9ydj2y73',
                    'bottom_right' => 'drj7teegpus6',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_bounding_box_query_as_wkt(): void
    {
        $geo = new GeoBoundingBox('location', 'BBOX (-74.1, -71.12, 40.73, 40.01)');

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'wkt' => 'BBOX (-74.1, -71.12, 40.73, 40.01)',
                ],
            ],
        ], $geo->toArray());
    }

    #[Test]
    public function it_builds_a_geo_bounding_box_query_as_wkt_with_options(): void
    {
        $geo = new GeoBoundingBox('location', 'BBOX (-74.1, -71.12, 40.73, 40.01)', [
            'validation_method' => GeoboundingBox::IGNORE_MALFORMED,
        ]);

        $this->assertEquals([
            'geo_bounding_box' => [
                'location' => [
                    'wkt' => 'BBOX (-74.1, -71.12, 40.73, 40.01)',
                ],
                'validation_method' => 'ignore_malformed',
            ],
        ], $geo->toArray());
    }
}
