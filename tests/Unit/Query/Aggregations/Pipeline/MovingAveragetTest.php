<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MovingAverage;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MovingAverageTest extends TestCase
{
    #[Test]
    public function it_builds_moving_avg_aggregation()
    {
        $movingAverage = new MovingAverage('the_sum');

        $this->assertEquals([
            'moving_avg' => [
                'buckets_path' => 'the_sum',
            ],
        ], $movingAverage->toArray());
    }

    #[Test]
    public function it_builds_moving_avg_with_options()
    {
        $movingAverage = new MovingAverage('the_sum', [
            'model' => MovingAverage::MODEL_HOLT,
            'gap_policy' => MovingAverage::GAP_INSERT_ZEROS,
            'window' => 10,
            'invalid_option_are_removed' => 10,
            'minimize' => true,
            'predict' => 5,
            'settings' => ['alpha' => 0.5],
        ]);

        $this->assertEquals([
            'moving_avg' => [
                'buckets_path' => 'the_sum',
                'model' => 'holt',
                'gap_policy' => 'insert_zeros',
                'window' => 10,
                'minimize' => true,
                'predict' => 5,
                'settings' => ['alpha' => 0.5],
            ],
        ], $movingAverage->toArray());
    }
}
