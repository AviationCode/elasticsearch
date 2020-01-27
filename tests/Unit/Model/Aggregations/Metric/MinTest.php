<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Min;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MinTest extends TestCase
{
    /** @test */
    public function it_translates_min_aggregation()
    {
        $min = new Min(['value' => 8]);

        $this->assertEquals(8, $min->value());
    }
}
