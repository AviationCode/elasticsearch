<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\CumulativeCardinality;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CumulativeCardinalityTest extends TestCase
{
    #[Test]
    public function it_builds_cumulative_cardinality_aggregation(): void
    {
        $bucket = new CumulativeCardinality('the_sum');

        $this->assertEquals([
            'cumulative_cardinality' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_cumulative_cardinality_aggregation_format(): void
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
