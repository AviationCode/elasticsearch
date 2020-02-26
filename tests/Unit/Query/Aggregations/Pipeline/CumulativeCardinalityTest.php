<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\CumulativeCardinality;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CumulativeCardinalityTest extends TestCase
{
    /** @test **/
    public function it_builds_cumulative_cardinality_aggregation()
    {
        $bucket = new CumulativeCardinality('the_sum');

        $this->assertEquals([
            'cumulative_cardinality' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_cumulative_cardinality_aggregation_format()
    {
        $bucket = new CumulativeCardinality('the_sum', '000.00');

        $this->assertEquals([
            'cumulative_cardinality' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
