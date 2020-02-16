<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MovingFunction;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MovingFunctionTest extends TestCase
{
    /** @test **/
    public function it_builds_moving_fn_aggregation()
    {
        $moving = new Movingfunction('the_sum', 10, 'MovingFunctions.min(values)');

        $this->assertEquals([
            'moving_fn' => [
                'buckets_path' => 'the_sum',
                'window' => 10,
                'script' => 'MovingFunctions.min(values)',
            ],
        ], $moving->toArray());
    }

    /** @test **/
    public function it_builds_moving_fn_with_shift()
    {
        $moving = new Movingfunction('the_sum', 10, 'MovingFunctions.min(values)', 5);

        $this->assertEquals([
            'moving_fn' => [
                'buckets_path' => 'the_sum',
                'window' => 10,
                'script' => 'MovingFunctions.min(values)',
                'shift' => 5,
            ],
        ], $moving->toArray());
    }
}
