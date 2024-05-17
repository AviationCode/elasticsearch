<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\GeotileGrid;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class GeotileGridTest extends TestCase
{
    #[Test]
    public function it_builds_a_geotile_grid_aggregation()
    {
        $grid = new GeotileGrid('geo');

        $this->assertEquals([
            'geotile_grid' => [
                'field' => 'geo',
            ],
        ], $grid->toArray());
    }

    #[Test]
    public function it_builds_a_geotile_grid_aggregation_with_valid_options()
    {
        $grid = new GeotileGrid('geo', [
            'precision' => 3,
            'bounds' => [
                'top_left' => '53.4375, 4.21875',
                'bottom_right' => '52.03125, 5.625',
            ],
            'size' => 1000,
            'shard_size' => 10,
        ]);

        $this->assertEquals([
            'geotile_grid' => [
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
    public function it_builds_a_geotile_grid_aggregation_with_invalid_options()
    {
        $grid = new GeotileGrid('geo', [
            'invalid' => 'options'
        ]);

        $this->assertEquals([
            'geotile_grid' => [
                'field' => 'geo',
            ],
        ], $grid->toArray());
    }
}
