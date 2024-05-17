<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Pagination;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Bucket;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Pagination\SimplePaginator;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Terms;
use AviationCode\Elasticsearch\Tests\Feature\TestCase;
use ReflectionMethod;

class SimplePaginatorTest extends TestCase
{
    #[Test]
    public function it_builds_a_simple_paginator(): void
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

        $result = ElasticCollection::parse($response);

        $paginator = new SimplePaginator($result, 1000, 1);

        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(10000, $paginator->total());
        $this->assertEquals(9, $paginator->lastPage());
    }

    #[Test]
    public function it_has_a_method_to_retrieve_the_aggregations_from_the_elastic_collection(): void
    {
        $aggregationsMethod = new ReflectionMethod(SimplePaginator::class, 'aggregations');

        $this->assertTrue($aggregationsMethod->isPublic());
        $this->assertSame(Aggregation::class, $aggregationsMethod->getReturnType()->getName());
        $this->assertSame(0, $aggregationsMethod->getNumberOfParameters());
    }

    #[Test]
    public function it_can_retrieve_the_aggregations(): void
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

        $result = ElasticCollection::parse($response);
        $aggregations = (new SimplePaginator($result, 1000, 1))->aggregations();

        $this->assertInstanceOf(Aggregation::class, $aggregations);
        $this->assertCount(0, $aggregations->toArray());

        $response = array_merge(
            $response,
            [
                'aggregations' => [
                    'terms#example_aggregation' => [
                        'doc_count_error_upper_bound' => 0,
                        'sum_other_doc_count' => 0,
                        'buckets' => [
                            [
                                'key' => 'ExampleValue',
                                'doc_count' => 10,
                            ],
                            [
                                'key' => 'ExampleValue2',
                                'doc_count' => 1,
                            ],
                        ],
                    ],
                ],
            ]
        );

        $aggregations = (new SimplePaginator(ElasticCollection::parse($response), 1000, 1))->aggregations();
        $this->assertCount(1, $aggregations->toArray());
        $this->assertInstanceOf(Bucket::class, $aggregations->get('example_aggregation'));
        $this->assertCount(2, $aggregations->get('example_aggregation'));
    }
}
