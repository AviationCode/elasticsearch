<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Bucket;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class BucketTest extends TestCase
{
    #[Test]
    public function it_can_build_a_bucket_aggregation(): void
    {
        $bucket = new Bucket([
            'doc_count_error_upper_bound' => 0,
            'sum_other_doc_count' => 75,
            'buckets' => [
                [
                    'key' => 'jeffreyway',
                    'doc_count' => 50,
                    'date_histogram#tweets_per_day' => [
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
                    ],
                ],
                [
                    'key' => 'adamwathan',
                    'doc_count' => 25,
                    'date_histogram#tweets_per_day' => [
                        'buckets' => [
                            [
                                'key_as_string' => '2019-12-08 00:00:00',
                                'key' => 1575763200000,
                                'doc_count' => 12,
                            ],
                            [
                                'key_as_string' => '2019-12-09 00:00:00',
                                'key' => 1575849600000,
                                'doc_count' => 13,
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertSame(0, $bucket->doc_count_error_upper_bound);
        $this->assertSame(75, $bucket->sum_other_doc_count);
        $this->assertNull($bucket->non_existing_key);

        $this->assertCount(2, $bucket);
        $this->assertTrue(isset($bucket[0]));
        $this->assertTrue(isset($bucket['jeffreyway']));
        $this->assertEquals('jeffreyway', $bucket->get(0)->key);
        $this->assertEquals(50, $bucket->get(0)->doc_count);
        $this->assertCount(2, $bucket->get(0)->tweets_per_day);

        $this->assertTrue(isset($bucket[1]));
        $this->assertTrue(isset($bucket['adamwathan']));
        $this->assertEquals('adamwathan', $bucket->get(1)->key);
        $this->assertEquals(25, $bucket->get(1)->doc_count);
        $this->assertCount(2, $bucket->get(1)->tweets_per_day);

        $jsonData = json_decode($bucket->toJson(), true);
        $this->assertEquals(0, $jsonData['meta']['doc_count_error_upper_bound']);
        $this->assertEquals(75, $jsonData['meta']['sum_other_doc_count']);
        $this->assertCount(2, $jsonData['data']);
    }

    #[Test]
    public function it_throws_exception_when_using_unset_on_read_only_array(): void
    {
        $bucket = new Bucket([
            'doc_count_error_upper_bound' => 0,
            'sum_other_doc_count' => 75,
            'buckets' => [
                [
                    'key' => 'jeffreyway',
                    'doc_count' => 50,
                ],
            ],
        ]);

        $this->expectException(\LogicException::class);

        unset($bucket[0]);
    }

    #[Test]
    public function it_throws_exception_when_using_setting_a_value_on_read_only_array(): void
    {
        $bucket = new Bucket([
            'doc_count_error_upper_bound' => 0,
            'sum_other_doc_count' => 75,
            'buckets' => [
                [
                    'key' => 'jeffreyway',
                    'doc_count' => 50,
                ],
            ],
        ]);

        $this->expectException(\LogicException::class);

        $bucket[0] = ['foo' => 'bar'];
    }

    #[Test]
    public function it_builds_geo_distance_response(): void
    {
        $geoDistance = new Bucket([
            'buckets' => [
                '*-100000.0' => [
                    "from" => 0.0,
                    "to" => 100000.0,
                    "doc_count" => 3
                ],
                "100000.0-300000.0" => [
                    "from" => 100000.0,
                    "to" => 300000.0,
                    "doc_count" => 1
                ],
                "300000.0-*" => [
                    "from" => 300000.0,
                    "doc_count" => 2
                ],
            ],
        ]);

        $this->assertCount(3, $geoDistance);
        $this->assertEquals(0.0, $geoDistance['*-100000.0']->from);
        $this->assertEquals(100000.0, $geoDistance['*-100000.0']->to);
        $this->assertEquals(3, $geoDistance['*-100000.0']->doc_count);

        $this->assertEquals(100000.0, $geoDistance['100000.0-300000.0']->from);
        $this->assertEquals(300000.0, $geoDistance['100000.0-300000.0']->to);
        $this->assertEquals(1, $geoDistance['100000.0-300000.0']->doc_count);

        $this->assertEquals(300000.0, $geoDistance['300000.0-*']->from);
        $this->assertNull($geoDistance['300000.0-*']->to);
        $this->assertEquals(2, $geoDistance['300000.0-*']->doc_count);
    }
}
