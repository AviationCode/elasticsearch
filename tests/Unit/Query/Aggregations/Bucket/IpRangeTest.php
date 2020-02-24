<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\IpRange;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class IpRangeTest extends TestCase
{
    /** @test **/
    public function it_builds_a_ip_range_bucket_aggregation()
    {
        $geoDistance = new IpRange('ip', [
            ['to' => '10.0.0.5'],
            ['from' => '10.0.0.5', 'to' => '10.0.0.128'],
            ['from' => '10.0.0.128'],
        ]);

        $this->assertEquals([
            'ip_range' => [
                'field' => 'ip',
                'keyed' => true,
                'ranges' => [
                    ['to' => '10.0.0.5'],
                    ['from' => '10.0.0.5', 'to' => '10.0.0.128'],
                    ['from' => '10.0.0.128'],
                ],
            ],
        ], $geoDistance->toArray());
    }

    /** @test **/
    public function it_can_add_ranges_dynamically()
    {
        $geoDistance = new IpRange('ip');
        $geoDistance->to('10.0.0.5');
        $geoDistance->range('10.0.0.5', '10.0.0.128');
        $geoDistance->from('10.0.0.128');

        $this->assertEquals([
            'ip_range' => [
                'field' => 'ip',
                'keyed' => true,
                'ranges' => [
                    ['to' => '10.0.0.5'],
                    ['from' => '10.0.0.5', 'to' => '10.0.0.128'],
                    ['from' => '10.0.0.128'],
                ],
            ],
        ], $geoDistance->toArray());
    }

    /** @test **/
    public function it_can_define_custom_keys()
    {
        $geoDistance = new IpRange('ip');
        $geoDistance->to('10.0.0.5', 'first');
        $geoDistance->range('10.0.0.5', '10.0.0.128', 'second');
        $geoDistance->from('10.0.0.128', 'third');

        $this->assertEquals([
            'ip_range' => [
                'field' => 'ip',
                'keyed' => true,
                'ranges' => [
                    ['to' => '10.0.0.5', 'key' => 'first'],
                    ['from' => '10.0.0.5', 'to' => '10.0.0.128', 'key' => 'second'],
                    ['from' => '10.0.0.128', 'key' => 'third'],
                ],
            ],
        ], $geoDistance->toArray());
    }
}
