<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\AvgBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AvgBucketTest extends TestCase
{
    /** @test **/
    public function it_builds_avg_bucket_aggregation()
    {
        $avgBucket = new AvgBucket('the_avg');

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
            ],
        ], $avgBucket->toArray());
    }

    /** @test **/
    public function it_builds_avg_bucket_aggregation_gap_policy()
    {
        $avgBucket = new AvgBucket('the_avg', AvgBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
                'gap_policy' => 'insert_zeros',
            ],
        ], $avgBucket->toArray());

        $avgBucket = new AvgBucket('the_avg', AvgBucket::GAP_SKIP);

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
                'gap_policy' => 'skip',
            ],
        ], $avgBucket->toArray());
    }

    /** @test **/
    public function it_builds_avg_bucket_aggregation_format()
    {
        $avgBucket = new AvgBucket('the_avg', null, '000.00');

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
                'format' => '000.00',
            ],
        ], $avgBucket->toArray());
    }

    /** @test **/
    public function it_builds_avg_bucket_aggregation_unit()
    {
        $avgBucket = new AvgBucket('the_avg', null, null, 'day');

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
                'unit' => 'day'
            ],
        ], $avgBucket->toArray());
    }
}
