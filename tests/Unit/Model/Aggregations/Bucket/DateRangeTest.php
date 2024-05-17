<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Bucket\DateRange;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;
use Carbon\Carbon;

class DateRangeTest extends TestCase
{
    #[Test]
    public function it_successfully_translates_a_date_range(): void
    {
        $dateRange = new DateRange([
            'buckets' => [
                [
                    'to' => 1.4436576E12,
                    'to_as_string' => '10-2015',
                    'doc_count' => 7,
                    'key' => '*-10-2015',
                ],
                [
                    'from' => 1.4436576E12,
                    'from_as_string' => '10-2015',
                    'doc_count' => 0,
                    'key' => '10-2015-*',
                ],
            ],
        ]);

        $this->assertCount(2, $dateRange);
        $this->assertEquals(7, $dateRange->first()->doc_count);
        $this->assertInstanceOf(Carbon::class, $dateRange->first()->to);
        $this->assertInstanceOf(Carbon::class, $dateRange->last()->from);
    }

    #[Test]
    public function it_successfully_translates_a_date_range_with_keyed_response(): void
    {
        $dateRange = new DateRange([
            'buckets' => [
                '*-10-2015' => [
                    'to' => 1.4436576E12,
                    'to_as_string' => '10-2015',
                    'doc_count' => 7,
                ],
                '10-2015-*' => [
                    'from' => 1.4436576E12,
                    'from_as_string' => '10-2015',
                    'doc_count' => 0,
                ],
            ],
        ]);

        /**
         * In case of keyed response, since
         * Bucket extends \Illuminate\Support\Collection
         * an array of buckets can be obtained by using: array_values helper
         * with all() Collection method on the $dateRange instance.
         * Because the $dateRange holds a collection of 'key' => bucket pairs.
         * and even if NOT used, the value will returned/injected
         * when using methods: first(), last() of the Collection class.
         */
        $buckets = array_values($dateRange->all());

        $this->assertCount(2, $dateRange);
        $this->assertEquals(7, $buckets[0]->doc_count);
        $this->assertInstanceOf(Carbon::class, $buckets[0]->to);
        $this->assertInstanceOf(Carbon::class, $buckets[1]->from);
    }
}
