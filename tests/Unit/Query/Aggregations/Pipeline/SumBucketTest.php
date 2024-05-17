<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\SumBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SumBucketTest extends TestCase
{
    #[Test]
    public function it_builds_sum_bucket_aggregation()
    {
        $bucket = new SumBucket('the_sum');

        $this->assertEquals([
            'sum_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_sum_bucket_aggregation_gap_policy()
    {
        $bucket = new SumBucket('the_sum', SumBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'sum_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new SumBucket('the_sum', SumBucket::GAP_SKIP);

        $this->assertEquals([
            'sum_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_sum_bucket_aggregation_format()
    {
        $bucket = new SumBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'sum_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
