<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Terms;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Terms as TermsQuery;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermsTest extends TestCase
{
    /** @test **/
    public function it_can_build_a_terms_aggregation()
    {
        $query = new TermsQuery('users');
        $query->dateHistogram('tweets_per_day', 'created_at', '1d');

        $terms = new Terms([
            'doc_count_error_upper_bound' => 0,
            'sum_other_doc_count'         => 75,
            'buckets'                     => [
                [
                    'key'                           => 'jeffreyway',
                    'doc_count'                     => 50,
                    'date_histogram#tweets_per_day' => [
                        'buckets' => [
                            [
                                'key_as_string' => '2019-12-08 00:00:00',
                                'key'           => 1575763200000,
                                'doc_count'     => 13,
                            ],
                            [
                                'key_as_string' => '2019-12-09 00:00:00',
                                'key'           => 1575849600000,
                                'doc_count'     => 37,
                            ],
                        ],
                    ],
                ],
                [
                    'key'                           => 'adamwatham',
                    'doc_count'                     => 25,
                    'date_histogram#tweets_per_day' => [
                        'buckets' => [
                            [
                                'key_as_string' => '2019-12-08 00:00:00',
                                'key'           => 1575763200000,
                                'doc_count'     => 12,
                            ],
                            [
                                'key_as_string' => '2019-12-09 00:00:00',
                                'key'           => 1575849600000,
                                'doc_count'     => 13,
                            ],
                        ],
                    ],
                ],
            ],
        ], $query);

        $this->assertSame(0, $terms->doc_count_error_upper_bound);
        $this->assertSame(75, $terms->sum_other_doc_count);
        $this->assertNull($terms->non_existing_key);

        $this->assertCount(2, $terms);
        $this->assertEquals('jeffreyway', $terms->get(0)->key);
        $this->assertEquals(50, $terms->get(0)->doc_count);
        $this->assertCount(2, $terms->get(0)->tweets_per_day);

        $this->assertEquals('adamwatham', $terms->get(1)->key);
        $this->assertEquals(25, $terms->get(1)->doc_count);
        $this->assertCount(2, $terms->get(1)->tweets_per_day);

        $jsonData = json_decode($terms->toJson(), true);
        $this->assertEquals(0, $jsonData['meta']['doc_count_error_upper_bound']);
        $this->assertEquals(75, $jsonData['meta']['sum_other_doc_count']);
        $this->assertCount(2, $jsonData['data']);
    }
}
