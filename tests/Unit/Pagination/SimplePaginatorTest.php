<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Pagination;

use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Pagination\SimplePaginator;
use AviationCode\Elasticsearch\Tests\Feature\TestCase;

class SimplePaginatorTest extends TestCase
{
    /** @test **/
    public function it_builds_a_simple_paginator()
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
}
