<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MaxBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MaxBucketTest extends TestCase
{
    /** @test **/
    public function it_builds_max_bucket_aggregation()
    {
        $bucket = new MaxBucket('the_sum');

        $this->assertEquals([
            'max_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_max_bucket_aggregation_gap_policy()
    {
        $bucket = new MaxBucket('the_sum', MaxBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'max_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new MaxBucket('the_sum', MaxBucket::GAP_SKIP);

        $this->assertEquals([
            'max_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    /** @test **/
    public function it_builds_max_bucket_aggregation_format()
    {
        $bucket = new MaxBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'max_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
