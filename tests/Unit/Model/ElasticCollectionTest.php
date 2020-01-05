<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model;

use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Query\Builder;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ElasticCollectionTest extends TestCase
{
    /** @test **/
    public function it_builds_up_elastic_collection()
    {
        $response = [
            'took' => 1,
            'timed_out' => false,
            '_shards' => [
                'total' => 1,
                'successful' => 1,
                'skipped' => 0,
                'failed' => 0,
            ],
            'hits' => [
                'total' => [
                    'value' => 10000,
                    'relation' => 'gte',
                ],
                'max_score' => 1.0,
                'hits' => [
                    [
                        '_index' => 'article',
                        '_type' => '_doc',
                        '_id' => 1,
                        '_score' => 1.0,
                        '_source' => [
                            'id' => 1,
                            'title' => 'My first title',
                            'body' => 'My first body',
                            'created_at' => '2019-12-20 12:00:00',
                            'updated_at' => '2019-12-20 12:00:00',
                        ],
                    ],
                    [
                        '_index' => 'article',
                        '_type' => '_doc',
                        '_id' => 2,
                        '_score' => 1.0,
                        '_source' => [
                            'id' => 2,
                            'title' => 'My second title',
                            'body' => 'My second body',
                            'created_at' => '2019-12-21 12:00:00',
                            'updated_at' => '2019-12-21 12:00:00',
                        ],
                    ],
                ],
            ],
        ];

        $result = ElasticCollection::parse($response, new Builder(), new ArticleTestModel);

        $this->assertEquals(1, $result->took);
        $this->assertEquals(1.0, $result->max_score);
        $this->assertEquals(10000, $result->total);
        $this->assertTrue($result->totalExceedsLimit());
        $this->assertEquals(1, $result->shards['total']);
        $this->assertEquals(1, $result->shards['successful']);
        $this->assertEquals(0, $result->shards['skipped']);
        $this->assertEquals(0, $result->shards['failed']);
        $this->assertFalse($result->timed_out);
    }

    /** @test **/
    public function it_maps_aggregations()
    {
        $response = [
            'took' => 1,
            'timed_out' => false,
            '_shards' => [
                'total' => 1,
                'successful' => 1,
                'skipped' => 0,
                'failed' => 0,
            ],
            'hits' => [
                'total' => [
                    'value' => 75,
                    'relation' => 'eq',
                ],
            ],
            'aggregations' => [
                'total' => [
                    'value' => 75,
                ],
                'users' => [
                    'doc_count_error_upper_bound' => 0,
                    'sum_other_doc_count' => 75,
                    'buckets' => [
                        [
                            'key' => 'jeffreyway',
                            'doc_count' => 50,
                            'tweets_per_day' => [
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
                            'key' => 'adamwatham',
                            'doc_count' => 25,
                            'tweets_per_day' => [
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
                ],
            ],
        ];

        $builder = new Builder();
        $builder->aggregations();

        $result = ElasticCollection::parse($response, $builder);

        $this->assertEquals(75, $result->aggregations->total->value());
//        $this->assertEquals(, $result->aggregations->users->value());
    }
}
