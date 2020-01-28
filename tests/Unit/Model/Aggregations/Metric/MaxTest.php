<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Max;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MaxTest extends TestCase
{
    /** @test */
    public function it_translate_max_aggregation()
    {
        $max = new Max(['value' => 8]);

        $this->assertEquals(8, $max->value());
    }
}
