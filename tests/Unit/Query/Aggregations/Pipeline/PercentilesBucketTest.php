<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\PercentilesBucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PercentilesBucketTest extends TestCase
{
    #[Test]
    public function it_builds_percentiles_bucket_aggregation()
    {
        $bucket = new PercentilesBucket('the_sum');

        $this->assertEquals([
            'percentiles_bucket' => [
                'buckets_path' => 'the_sum',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_percentiles_bucket_aggregation_gap_policy()
    {
        $bucket = new PercentilesBucket('the_sum', PercentilesBucket::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'percentiles_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $bucket->toArray());

        $bucket = new PercentilesBucket('the_sum', PercentilesBucket::GAP_SKIP);

        $this->assertEquals([
            'percentiles_bucket' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_percentiles_bucket_aggregation_format()
    {
        $bucket = new PercentilesBucket('the_sum', null, '000.00');

        $this->assertEquals([
            'percentiles_bucket' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $bucket->toArray());
    }

    #[Test]
    public function it_builds_percentiles_bucket_aggregation_percentiles()
    {
        $bucket = new PercentilesBucket('the_sum', null, null, [25.0, 50.0, 75.0]);

        $this->assertEquals([
            'percentiles_bucket' => [
                'buckets_path' => 'the_sum',
                'percents' => [25.0, 50.0, 75.0],
            ],
        ], $bucket->toArray());
    }
}
