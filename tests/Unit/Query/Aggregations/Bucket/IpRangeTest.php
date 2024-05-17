<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\IpRange;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class IpRangeTest extends TestCase
{
    #[Test]
    public function it_builds_a_ip_range_bucket_aggregation()
    {
        $range = new IpRange('ip', [
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
        ], $range->toArray());
    }

    #[Test]
    public function it_can_add_ranges_dynamically()
    {
        $range = new IpRange('ip');
        $range->to('10.0.0.5');
        $range->range('10.0.0.5', '10.0.0.128');
        $range->from('10.0.0.128');

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
        ], $range->toArray());
    }

    #[Test]
    public function it_can_define_custom_keys()
    {
        $range = new IpRange('ip');
        $range->to('10.0.0.5', 'first');
        $range->range('10.0.0.5', '10.0.0.128', 'second');
        $range->from('10.0.0.128', 'third');

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
        ], $range->toArray());
    }
}
