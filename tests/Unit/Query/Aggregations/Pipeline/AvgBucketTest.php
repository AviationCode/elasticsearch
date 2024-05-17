<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\AvgBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class AvgBucketTest extends TestCase
{
    #[Test]
    public function it_builds_avg_bucket_aggregation(): void
    {
        $avgBucket = new AvgBucket('the_avg');

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
            ],
        ], $avgBucket->toArray());
    }

    #[Test]
    public function it_builds_avg_bucket_aggregation_gap_policy(): void
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

    #[Test]
    public function it_builds_avg_bucket_aggregation_format(): void
    {
        $avgBucket = new AvgBucket('the_avg', null, '000.00');

        $this->assertEquals([
            'avg_bucket' => [
                'buckets_path' => 'the_avg',
                'format' => '000.00',
            ],
        ], $avgBucket->toArray());
    }

    #[Test]
    public function it_builds_avg_bucket_aggregation_unit(): void
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
