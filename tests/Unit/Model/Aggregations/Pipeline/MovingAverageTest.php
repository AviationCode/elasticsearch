<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Model\Aggregations\Pipeline\MovingAverage;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MovingAverageTest extends TestCase
{
    /** @test **/
    public function it_translates_derivative()
    {
        $moving = new MovingAverage(['value' => -0.25]);

        $this->assertSame(-0.25, $moving->value);
    }
}
