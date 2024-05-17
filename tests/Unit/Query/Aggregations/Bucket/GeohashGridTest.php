<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GeohashGrid;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeohashGridTest extends TestCase
{
    #[Test]
    public function it_builds_a_geohash_grid_aggregation()
    {
        $grid = new GeohashGrid('geo');

        $this->assertEquals([
            'geohash_grid' => [
                'field' => 'geo',
            ],
        ], $grid->toArray());
    }

    #[Test]
    public function it_builds_a_geohash_grid_aggregation_with_valid_options()
    {
        $grid = new GeohashGrid('geo', [
            'precision' => 3,
            'bounds' => [
                'top_left' => '53.4375, 4.21875',
                'bottom_right' => '52.03125, 5.625',
            ],
            'size' => 1000,
            'shard_size' => 10,
        ]);

        $this->assertEquals([
            'geohash_grid' => [
                'field' => 'geo',
                'precision' => 3,
                'bounds' => [
                    'top_left' => '53.4375, 4.21875',
                    'bottom_right' => '52.03125, 5.625',
                ],
                'size' => 1000,
                'shard_size' => 10,
            ],
        ], $grid->toArray());
    }

    #[Test]
    public function it_builds_a_geohash_grid_aggregation_with_invalid_options()
    {
        $grid = new GeohashGrid('geo', [
            'invalid' => 'options'
        ]);

        $this->assertEquals([
            'geohash_grid' => [
                'field' => 'geo',
            ],
        ], $grid->toArray());
    }
}
