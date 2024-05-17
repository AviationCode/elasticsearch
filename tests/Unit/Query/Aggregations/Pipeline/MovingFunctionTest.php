<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MovingFunction;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MovingFunctionTest extends TestCase
{
    #[Test]
    public function it_builds_moving_fn_aggregation(): void
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

    #[Test]
    public function it_builds_moving_fn_with_shift(): void
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
