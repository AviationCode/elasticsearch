<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Bucket\DateHistogram;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DateHistogram as DateHistogramQuery;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;
use Carbon\Carbon;

class DateHistogramTest extends TestCase
{
    #[Test]
    public function it_builds_a_date_histogram(): void
    {
        $query = new DateHistogramQuery('created_at', '1d');

        $histogram = new DateHistogram([
            'buckets' => [
                [
                    'key_as_string' => '2019-12-08 00:00:00',
                    'key' => 1575763200000,
                    'doc_count' => 13,
                ],
                [
                    'key_as_string' => '2019-12-09 00:00:00',
                    'key' => 1575849600000,
                    'doc_count' => 37,
                ],
            ],
        ], $query);

        $this->assertCount(2, $histogram);
        $this->assertInstanceOf(Carbon::class, $histogram->get(0)->date);
        $this->assertEquals(13, $histogram->get(0)->doc_count);
    }
}
