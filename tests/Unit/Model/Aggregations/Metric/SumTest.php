<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Sum;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SumTest extends TestCase
{
    /** @test */
    public function it_translates_sum_aggregation()
    {
        $sum = new Sum(['value' => 450.0]);

        $this->assertEquals(450.0, $sum->value());
    }
}
