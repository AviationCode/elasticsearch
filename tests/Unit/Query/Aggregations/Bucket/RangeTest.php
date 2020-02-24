<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Range;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class RangeTest extends TestCase
{
    /** @test **/
    public function it_builds_a_range_bucket_aggregation()
    {
        $range = new Range('price', [
            ['to' => 100],
            ['from' => 100, 'to' => 200],
            ['from' => 200],
        ]);

        $this->assertEquals([
            'range' => [
                'field' => 'price',
                'keyed' => true,
                'ranges' => [
                    ['to' => 100],
                    ['from' => 100, 'to' => 200],
                    ['from' => 200],
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function it_can_add_ranges_dynamically()
    {
        $range = new Range('price');
        $range->to(100);
        $range->range(100, 200);
        $range->from(200);

        $this->assertEquals([
            'range' => [
                'field' => 'price',
                'keyed' => true,
                'ranges' => [
                    ['to' => 100],
                    ['from' => 100, 'to' => 200],
                    ['from' => 200],
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function it_can_define_custom_keys()
    {
        $range = new Range('price');
        $range->to(100, 'first');
        $range->range(100, 200, 'second');
        $range->from(200, 'third');

        $this->assertEquals([
            'range' => [
                'field' => 'price',
                'keyed' => true,
                'ranges' => [
                    ['to' => 100, 'key' => 'first'],
                    ['from' => 100, 'to' => 200, 'key' => 'second'],
                    ['from' => 200, 'key' => 'third'],
                ],
            ],
        ], $range->toArray());
    }
}
