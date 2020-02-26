<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\CumulativeSum;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CumulativeSumTest extends TestCase
{
    /** @test **/
    public function it_builds_cumulative_sum_aggregation()
    {
        $bucket = new CumulativeSum('the_sum');

        $this->assertEquals([
            'cumulative_sum' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_cumulative_sum_aggregation_format()
    {
        $bucket = new CumulativeSum('the_sum', '000.00');

        $this->assertEquals([
            'cumulative_sum' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
