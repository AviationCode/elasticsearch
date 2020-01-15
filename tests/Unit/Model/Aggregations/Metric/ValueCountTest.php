<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use AviationCode\Elasticsearch\Model\Aggregations\Metric\ValueCount;
use AviationCode\Elasticsearch\Query\Aggregations\Metric\ValueCount as ValueCountQuery;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ValueCountTest extends TestCase
{
    /** @test **/
    public function it_translates_value_count()
    {
        $valueCount = new ValueCount(['value' => 8], new ValueCountQuery('some-field'));

        $this->assertEquals(8, $valueCount->value());
    }
}
