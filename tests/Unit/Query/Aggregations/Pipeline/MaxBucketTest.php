<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MaxBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class MaxBucketTest extends TestCase
{
    #[Test]
    public function it_builds_max_bucket_aggregation(): void
    {
        $bucket = new MaxBucket('the_sum');

        $this->assertEquals([
            'max_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_max_bucket_aggregation_gap_policy(): void
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

    #[Test]
    public function it_builds_max_bucket_aggregation_format(): void
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
