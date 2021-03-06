<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\Percentiles;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PercentilesTest extends TestCase
{
    /** @test */
    public function it_translates_percentiles_aggregation()
    {
        $value = [
            'values' => [
                '1.0' => 5.0,
                '5.0' => 25.0,
                '25.0' => 165.0,
                '50.0' => 445.0,
                '75.0' => 725.0,
                '95.0' => 945.0,
                '99.0' => 985.0
            ],
        ];

        $percentiles = new Percentiles($value);

        $this->assertSame(5.0, $percentiles['1.0']);
        $this->assertSame(25.0, $percentiles['5.0']);
        $this->assertSame(165.0, $percentiles['25.0']);
        $this->assertSame(445.0, $percentiles['50.0']);
        $this->assertSame(725.0, $percentiles['75.0']);
        $this->assertSame(945.0, $percentiles['95.0']);
        $this->assertSame(985.0, $percentiles['99.0']);
    }
}
