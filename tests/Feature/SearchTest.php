<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;
use Carbon\Carbon;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class SearchTest extends TestCase
{
    /**
     * @var Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->instance('elasticsearch.client', $this->client = \Mockery::mock(Client::class));
    }

    #[Test]
    public function it_performs_search(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                ],
            ])
            ->andReturn([
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
            ]);

        $qb = $this->elastic->query(Article::class);

        $result = $qb->limit(2)->get();

        $this->assertInstanceOf(ElasticCollection::class, $result);
        $this->assertEquals(2, $result->count());

        // Verify collection result
        $this->assertEquals(1, $result->took);
        $this->assertEquals(1.0, $result->max_score);
        $this->assertEquals(10000, $result->total);
        $this->assertTrue($result->totalExceedsLimit());
        $this->assertEquals(1, $result->shards['total']);
        $this->assertEquals(1, $result->shards['successful']);
        $this->assertEquals(0, $result->shards['skipped']);
        $this->assertEquals(0, $result->shards['failed']);
        $this->assertFalse($result->timed_out);

        tap($result->get(0), function ($model) {
            $this->assertEquals(1, $model->getKey());
            $this->assertEquals(1, $model->id);
            $this->assertEquals('My first title', $model->title);
            $this->assertEquals('My first body', $model->body);
            $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->created_at);
            $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->updated_at);

            $this->assertEquals(1.0, $model->getElasticAttribute('score'));
            $this->assertEquals(1.0, $model->elastic['score']);
            $this->assertEquals('_doc', $model->getElasticAttribute('type'));
            $this->assertEquals('_doc', $model->elastic['type']);
            $this->assertEquals('article', $model->getElasticAttribute('index'));
            $this->assertEquals('article', $model->elastic['index']);
            $this->assertEquals(1, $model->getElasticAttribute('id'));
            $this->assertEquals(1, $model->elastic['id']);
        });

        tap($result->get(1), function ($model) {
            $this->assertEquals(2, $model->getKey());
            $this->assertEquals(2, $model->id);
            $this->assertEquals('My second title', $model->title);
            $this->assertEquals('My second body', $model->body);
            $this->assertEquals(Carbon::create(2019, 12, 21, 12), $model->created_at);
            $this->assertEquals(Carbon::create(2019, 12, 21, 12), $model->updated_at);

            $this->assertEquals(1.0, $model->getElasticAttribute('score'));
            $this->assertEquals(1.0, $model->elastic['score']);
            $this->assertEquals('_doc', $model->getElasticAttribute('type'));
            $this->assertEquals('_doc', $model->elastic['type']);
            $this->assertEquals('article', $model->getElasticAttribute('index'));
            $this->assertEquals('article', $model->elastic['index']);
            $this->assertEquals(2, $model->getElasticAttribute('id'));
            $this->assertEquals(2, $model->elastic['id']);
        });
    }

    #[Test]
    public function it_performs_search_without_eloquent_model(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                ],
            ])
            ->andReturn([
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
            ]);

        $qb = $this->elastic->query('article');

        $result = $qb->limit(2)->get();

        $this->assertInstanceOf(ElasticCollection::class, $result);
        $this->assertEquals(2, $result->count());

        // Verify collection result
        $this->assertEquals(1, $result->took);
        $this->assertEquals(1.0, $result->max_score);
        $this->assertEquals(10000, $result->total);
        $this->assertTrue($result->totalExceedsLimit());
        $this->assertEquals(1, $result->shards['total']);
        $this->assertEquals(1, $result->shards['successful']);
        $this->assertEquals(0, $result->shards['skipped']);
        $this->assertEquals(0, $result->shards['failed']);
        $this->assertFalse($result->timed_out);

        tap($result->get(0), function ($model) {
            $this->assertEquals(1, $model->getKey());
            $this->assertEquals(1, $model->id);
            $this->assertEquals('My first title', $model->title);
            $this->assertEquals('My first body', $model->body);
            $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->created_at);
            $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->updated_at);

            $this->assertEquals(1.0, $model->getElasticAttribute('score'));
            $this->assertEquals(1.0, $model->elastic['score']);
            $this->assertEquals('_doc', $model->getElasticAttribute('type'));
            $this->assertEquals('_doc', $model->elastic['type']);
            $this->assertEquals('article', $model->getElasticAttribute('index'));
            $this->assertEquals('article', $model->elastic['index']);
            $this->assertEquals(1, $model->getElasticAttribute('id'));
            $this->assertEquals(1, $model->elastic['id']);
        });

        tap($result->get(1), function ($model) {
            $this->assertEquals(2, $model->getKey());
            $this->assertEquals(2, $model->id);
            $this->assertEquals('My second title', $model->title);
            $this->assertEquals('My second body', $model->body);
            $this->assertEquals(Carbon::create(2019, 12, 21, 12), $model->created_at);
            $this->assertEquals(Carbon::create(2019, 12, 21, 12), $model->updated_at);

            $this->assertEquals(1.0, $model->getElasticAttribute('score'));
            $this->assertEquals(1.0, $model->elastic['score']);
            $this->assertEquals('_doc', $model->getElasticAttribute('type'));
            $this->assertEquals('_doc', $model->elastic['type']);
            $this->assertEquals('article', $model->getElasticAttribute('index'));
            $this->assertEquals('article', $model->elastic['index']);
            $this->assertEquals(2, $model->getElasticAttribute('id'));
            $this->assertEquals(2, $model->elastic['id']);
        });
    }

    #[Test]
    public function it_finds_by_id(): void
    {
        $this->client->shouldReceive('get')
            ->with([
                'index' => 'article',
                'id' => 1,
            ])
            ->andReturn([
                '_index' => 'article',
                '_type' => '_doc',
                '_id' => '1',
                '_version' => 1,
                '_seq_no' => 0,
                '_primary_term' => 1,
                'found' => true,
                '_source' => [
                    'id' => 1,
                    'title' => 'My first title',
                    'body' => 'My first body',
                    'created_at' => '2019-12-20 12:00:00',
                    'updated_at' => '2019-12-20 12:00:00',
                ],
            ]);

        $qb = $this->elastic->query(Article::class);

        $model = $qb->find(1);

        $this->assertEquals(1, $model->getKey());
        $this->assertEquals(1, $model->id);
        $this->assertEquals('My first title', $model->title);
        $this->assertEquals('My first body', $model->body);
        $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->created_at);
        $this->assertEquals(Carbon::create(2019, 12, 20, 12), $model->updated_at);

        $this->assertEquals('_doc', $model->getElasticAttribute('type'));
        $this->assertEquals('_doc', $model->elastic['type']);
        $this->assertEquals('article', $model->getElasticAttribute('index'));
        $this->assertEquals('article', $model->elastic['index']);
        $this->assertEquals(1, $model->getElasticAttribute('id'));
        $this->assertEquals(1, $model->elastic['id']);

        $this->assertEquals($model, $qb->findOrFail(1));
    }

    #[Test]
    public function it_returns_null_when_find_does_not_return_result(): void
    {
        $this->client->shouldReceive('get')
            ->once()
            ->with([
                'index' => 'article',
                'id' => 0,
            ])
            ->andReturn([
                '_index' => 'article',
                '_type' => '_doc',
                '_id' => '0',
                'found' => false,
            ]);

        $qb = $this->elastic->query(Article::class);

        $result = $qb->find(0);

        $this->assertNull($result);
    }

    #[Test]
    public function it_throws_model_not_found_exception_when_findOrFail_does_not_yield_a_result(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->client->shouldReceive('get')
            ->once()
            ->with([
                'index' => 'article',
                'id' => 1,
            ])
            ->andReturn([
                '_index' => 'article',
                '_type' => '_doc',
                '_id' => '1',
                'found' => false,
            ]);

        $qb = $this->elastic->query(Article::class);

        $qb->findOrFail(1);

        $this->markSuccessfull();
    }

    #[Test]
    public function it_orders_by_latest_created_at(): void
    {
        $this->client->shouldReceive('search')
            ->once()
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 100,
                    'sort' => [['created_at' => 'desc']],
                ],
            ])
            ->andReturn($this->successResponse());

        $qb = $this->elastic->query(Article::class);

        $results = $qb->latest()->get();

        $this->assertEquals(2, $results->count());
    }

    #[Test]
    public function it_gets_first_result(): void
    {
        $this->client->shouldReceive('search')
            ->once()
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 1,
                ],
            ])
            ->andReturn($this->successResponse());

        $qb = $this->elastic->query(Article::class);

        $result = $qb->first();

        $this->assertInstanceOf(Article::class, $result);
    }

    /**
     * This test case covers example described in
     * https://www.elastic.co/guide/en/elasticsearch/reference/current/query-filter-context.html.
     *
     * This example wraps match query search string in "query" object to make request consistent with other
     * elasticsearch requests.
     */
    #[Test]
    public function complex_query(): void
    {
        $this->client->shouldReceive('search')
            ->once()
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 100,
                    'query' => [
                        'bool' => [
                            'must' => [
                                ['match' => ['title' => ['query' => 'Search']]],
                                ['match' => ['content' => ['query' => 'Elasticsearch']]],
                            ],
                            'filter' => [
                                ['term' => ['status' => ['value' => 'published']]],
                                ['range' => ['publish_date' => ['gte' => '2015-01-01']]],
                            ],
                        ],
                    ],
                ],
            ])
            ->andReturn($this->successResponse());

        $qb = $this->elastic->query(Article::class);

        $results = $qb->must(function (Must $must) {
            return $must->match('title', 'Search')
                ->match('content', 'Elasticsearch');
        })->filter(function (Filter $filter) {
            return $filter->term('status', 'published')
                ->range('publish_date', '>=', '2015-01-01');
        })->get();

        $this->assertEquals(2, $results->count());
    }

    #[Test]
    public function it_chunks_over_large_data_set(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'sort' => [
                        ['id' => 'asc']
                    ],
                ],
                'typed_keys' => true,
            ])
            ->andReturn($this->successResponse());

        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'query' => [
                        'bool' => [
                            'filter' => [
                                [
                                    'range' => [
                                        'id' => [
                                            'gt' => 2,
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sort' => [
                        ['id' => 'asc']
                    ]
                ],
            ])
            ->once()
            ->andReturn($this->successResponse());

        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'query' => [
                        'bool' => [
                            'filter' => [
                                [
                                    'range' => [
                                        'id' => [
                                            'gt' => 2,
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sort' => [
                        ['id' => 'asc']
                    ]
                ],
            ])
            ->once()
            ->andReturn([
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
                        'value' => 0,
                        'relation' => 'eq',
                    ],
                    'max_score' => 1.0,
                    'hits' => [],
                ],
            ]);

        $qb = $this->elastic->query(Article::class);

        $times = 0;
        $total = 0;

        $qb->chunk(2, function ($hits) use (&$times, &$total) {
            $this->assertInstanceOf(ElasticCollection::class, $hits);
            $total += $hits->count();

            $times++;
        });

        $this->assertEquals(4, $total);
        $this->assertEquals(2, $times);
    }

    #[Test]
    public function it_can_each_by_id_through_all_records(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'sort' => [
                        ['id' => 'asc']
                    ],
                ],
                'typed_keys' => true,
            ])
            ->andReturn($this->successResponse());

        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'query' => [
                        'bool' => [
                            'filter' => [
                                [
                                    'range' => [
                                        'id' => [
                                            'gt' => 2,
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sort' => [
                        ['id' => 'asc']
                    ]
                ],
            ])
            ->once()
            ->andReturn($this->successResponse());

        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'typed_keys' => true,
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'query' => [
                        'bool' => [
                            'filter' => [
                                [
                                    'range' => [
                                        'id' => [
                                            'gt' => 2,
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'sort' => [
                        ['id' => 'asc']
                    ]
                ],
            ])
            ->once()
            ->andReturn([
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
                        'value' => 0,
                        'relation' => 'eq',
                    ],
                    'max_score' => 1.0,
                    'hits' => [],
                ],
            ]);

        $qb = $this->elastic->query(Article::class);

        $total = 0;

        $qb->eachById(function ($hit) use (&$total) {
            $this->assertInstanceOf(Article::class, $hit);
            $total++;
        }, 2);

        $this->assertEquals(4, $total);
    }

    #[Test]
    public function it_can_early_return_from_each_method(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'sort' => [
                        ['id' => 'asc']
                    ],
                ],
                'typed_keys' => true,
            ])
            ->andReturn($this->successResponse());


        $qb = $this->elastic->query(Article::class);

        $total = 0;

        $qb->eachById(function ($hit) use (&$total) {
            $this->assertInstanceOf(Article::class, $hit);
            $total++;

            return false;
        }, 2);

        $this->assertEquals(1, $total);
    }

    #[Test]
    public function it_can_early_return_from_chunk(): void
    {
        $this->client->shouldReceive('search')
            ->with([
                'index' => 'article',
                'body' => [
                    'from' => 0,
                    'size' => 2,
                    'sort' => [
                        ['id' => 'asc']
                    ],
                ],
                'typed_keys' => true,
            ])
            ->andReturn($this->successResponse());


        $qb = $this->elastic->query(Article::class);

        $total = 0;

        $qb->chunk(2, function ($hits) use (&$total) {
            $this->assertInstanceOf(ElasticCollection::class, $hits);
            $total += $hits->count();

            return false;
        });

        $this->assertEquals(2, $total);
    }

    private function successResponse()
    {
        return [
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
    }
}
