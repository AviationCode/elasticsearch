<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SumTest extends TestCase
{
    #[Test]
    public function it_builds_a_sum_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->sum('hat_prices', 'price');
        $aggs->sum('watch_prices', 'price', ['missing' => 100]);

        $this->assertEquals([
            'hat_prices' => ['sum' => ['field' => 'price']],
            'watch_prices' => ['sum' => ['field' => 'price', 'missing' => 100]],
        ], $aggs->toArray());
    }
}
