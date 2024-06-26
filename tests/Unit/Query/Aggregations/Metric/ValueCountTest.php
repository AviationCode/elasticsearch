<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class ValueCountTest extends TestCase
{
    #[Test]
    public function it_builds_a_value_count_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->valueCount('types_count', 'type');

        $this->assertEquals([
            'types_count' => ['value_count' => ['field' => 'type']],
        ], $aggs->toArray());
    }
}
