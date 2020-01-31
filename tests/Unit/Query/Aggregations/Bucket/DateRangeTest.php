<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class DateRangeTest extends TestCase
{
    /** @test **/
    public function it_builds_a_date_range_aggregation()
    {
        $aggs = new Aggregation();
        $ranges = [
            ['to' => 'now-10M/M'],
            ['from' => 'now-10M/M'],
        ];

        /** Basic */
        $aggs->dateRange('range', 'date', $ranges);
        /** With format option */
        $aggs->dateRange('updated_at_range', 'updated_at', $ranges, ['format' => 'MM-yyyy']);
        /** with time_zone option */
        $aggs->dateRange('deleted_at_range', 'deleted_at', $ranges, ['time_zone' => 'CET']);
        /** with keyed option set to true */
        $aggs->dateRange('created_at_range', 'created_at', $ranges, ['keyed' => true]);

        /** Assertions */
        $this->assertEquals([
            'range' => ['date_range' => ['field' => 'date', 'ranges' => $ranges]],
            'updated_at_range' => ['date_range' => ['field' => 'updated_at', 'ranges' => $ranges, 'format' => 'MM-yyyy']],
            'deleted_at_range' => ['date_range' => ['field' => 'deleted_at', 'ranges' => $ranges, 'time_zone' => 'CET']],
            'created_at_range' => ['date_range' => ['field' => 'created_at', 'ranges' => $ranges, 'keyed' => true]],
        ], $aggs->toArray());

    }
}
