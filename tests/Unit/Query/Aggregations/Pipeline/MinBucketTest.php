<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\MinBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MinBucketTest extends TestCase
{
    #[Test]
    public function it_builds_min_bucket_aggregation()
    {
        $bucket = new MinBucket('the_sum');

        $this->assertEquals([
            'min_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_min_bucket_aggregation_gap_policy()
    {
        $bucket = new MinBucket('the_sum', MinBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'min_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new MinBucket('the_sum', MinBucket::GAP_SKIP);

        $this->assertEquals([
            'min_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_min_bucket_aggregation_format()
    {
        $bucket = new MinBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'min_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
