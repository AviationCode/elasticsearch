<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\StatsBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class StatsBucketTest extends TestCase
{
    #[Test]
    public function it_builds_stats_bucket_aggregation(): void
    {
        $bucket = new StatsBucket('the_sum');

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_stats_bucket_aggregation_gap_policy(): void
    {
        $bucket = new StatsBucket('the_sum', StatsBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new StatsBucket('the_sum', StatsBucket::GAP_SKIP);

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_stats_bucket_aggregation_format(): void
    {
        $bucket = new StatsBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'stats_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }
}
