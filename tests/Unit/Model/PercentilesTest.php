<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Metric\Percentiles;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PercentilesTest extends TestCase
{
    #[Test]
    public function it_can_access_values_by_key()
    {
        $percentiles = new Percentiles([
            'values' => [
                '1.0' => 171,
                '5.0' => 202,
                '25.0' => 313,
                '50.0' => 452,
                '75.0' => 661,
                '95.0' => 941,
                '99.0' => 1112,
            ]
        ]);

        $this->assertEquals(171, $percentiles['1.0']);
        $this->assertEquals(202, $percentiles['5.0']);
        $this->assertEquals(313, $percentiles['25.0']);
        $this->assertEquals(452, $percentiles['50.0']);
        $this->assertEquals(661, $percentiles['75.0']);
        $this->assertEquals(941, $percentiles['95.0']);
        $this->assertEquals(1112, $percentiles['99.0']);
    }
}
