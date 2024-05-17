<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Events\BulkDocumentsEvent;
use AviationCode\Elasticsearch\Events\DocumentCreatedEvent;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Facades\Event;

class IndexDocumentsTest extends TestCase
{
    #[Test]
    public function it_can_index_a_model(): void
    {
        Event::fake();

        $data = [
            'id' => 123,
            'title' => 'My title',
            'body' => 'My body',
        ];

        $article = new Article();
        $article->fill($data);

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => $data,
            ])
            ->once()
            ->andReturn([
                'result' => 'created',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $this->assertTrue($article->elastic()->add());
        Event::assertNotDispatched(DocumentCreatedEvent::class);
    }

    #[Test]
    public function it_indexes_a_php_array(): void
    {
        Event::fake();

        $data = [
            'id' => 123,
            'title' => 'My title',
            'body' => 'My body',
        ];

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => $data,
            ])
            ->once()
            ->andReturn([
                'result' => 'created',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $result = $this->elastic->add('article', $data);

        $this->assertTrue($result);
        Event::assertNotDispatched(DocumentCreatedEvent::class);
    }

    #[Test]
    public function it_indexes_a_stdClass(): void
    {
        Event::fake();

        $data = new \stdClass();
        $data->id = 123;
        $data->title = 'My title';
        $data->body = 'My body';

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => (array) $data,
            ])
            ->once()
            ->andReturn([
                'result' => 'created',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $result = $this->elastic->add('article', $data);

        $this->assertTrue($result);
        Event::assertNotDispatched(DocumentCreatedEvent::class);
    }

    #[Test]
    public function it_indexes_multiple_php_arrays_in_bulk(): void
    {
        Event::fake();
        Elasticsearch::enableEvents();

        $articles = [
            [
                'id' => 123,
                'title' => 'My title Article A',
                'body' => 'My body Article A',
            ], [
                'id' => 456,
                'title' => 'My title Article B',
                'body' => 'My body Article B',
            ],
        ];

        $this->elastic->getClient()->shouldReceive('bulk')
            ->with([
                'refresh' => true,
                'body' => implode(PHP_EOL, [
                    json_encode(['index' => ['_index' => 'article', '_id' => 123]]),
                    json_encode(['id' => 123, 'title' => 'My title Article A', 'body' => 'My body Article A']),
                    json_encode(['index' => ['_index' => 'article', '_id' => 456]]),
                    json_encode(['id' => 456, 'title' => 'My title Article B', 'body' => 'My body Article B']),
                ]).PHP_EOL,
            ])
            ->once()
            ->andReturn([
                'items' => [
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                ],
            ]);

        $this->assertTrue($this->elastic->bulk('article', $articles));

        Event::assertDispatchedTimes(BulkDocumentsEvent::class, 1);
    }

    #[Test]
    public function it_indexes_multiple_classes_in_bulk(): void
    {
        Event::fake();
        Elasticsearch::enableEvents();

        $articles = [
            new class {
                public $id = 123;
                public $title = 'My title Article A';
                public $body = 'My body Article A';
            },
            new class {
                public $id = 456;
                public $title = 'My title Article B';
                public $body = 'My body Article B';
            },
        ];

        $this->elastic->getClient()->shouldReceive('bulk')
            ->with([
                'refresh' => true,
                'body' => implode(PHP_EOL, [
                    json_encode(['index' => ['_index' => 'article', '_id' => 123]]),
                    json_encode(['id' => 123, 'title' => 'My title Article A', 'body' => 'My body Article A']),
                    json_encode(['index' => ['_index' => 'article', '_id' => 456]]),
                    json_encode(['id' => 456, 'title' => 'My title Article B', 'body' => 'My body Article B']),
                ]).PHP_EOL,
            ])
            ->once()
            ->andReturn([
                'items' => [
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                ],
            ]);

        $this->assertTrue($this->elastic->add('article', $articles));

        Event::assertDispatchedTimes(BulkDocumentsEvent::class, 1);
    }

    #[Test]
    public function it_can_index_a_model_given_as_param(): void
    {
        Event::fake();

        $articleA = new Article();
        $articleA->fill(['id' => 123]);
        $articleB = new Article();
        $articleB->fill(['id' => 456]);

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 456,
                'index' => 'article',
                'body' => [
                    'id' => 456,
                ],
            ])
            ->once()
            ->andReturn([
                'result' => 'created',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $this->assertTrue($articleA->elastic()->add($articleB));

        Event::assertNotDispatched(DocumentCreatedEvent::class);
    }

    #[Test]
    public function it_throws_exception_if_no_model_is_given(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->elastic = Elasticsearch::fake();

        $this->elastic->add();
    }

    #[Test]
    public function it_throws_exception_if_the_index_does_not_exist(): void
    {
        // This only occurs if the settings "action.auto_create_index" is set to false in elastic.
        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => [
                    'id' => 123,
                ],
            ])
            ->once()
            ->andThrow(new Missing404Exception(json_encode([
                'error' => [
                    'root_cause' => [
                        'type' => 'index_not_found_exception',
                        'reason' => 'no such index [article]',
                        'resource.type' => 'index_or_alias',
                        'resource.id' => 'article',
                        'index' => 'article',
                    ],
                    'type' => 'index_not_found_exception',
                    'reason' => 'no such index [article]',
                    'resource.type' => 'index_or_alias',
                    'resource.id' => 'article',
                    'index' => 'article',
                ],
                'status' => 404,
            ])));

        $article = new Article();
        $article->id = 123;

        $this->expectException(IndexNotFoundException::class);
        $article->elastic()->add();
    }

    #[Test]
    public function it_fires_document_created_event(): void
    {
        Event::fake();
        Elasticsearch::enableEvents();

        $data = [
            'id' => 123,
            'title' => 'My title',
            'body' => 'My body',
        ];

        $article = new Article();
        $article->fill($data);

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => $data,
            ])
            ->once()
            ->andReturn([
                'result' => 'created',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $this->assertTrue($article->elastic()->add());
        Event::assertDispatchedTimes(DocumentCreatedEvent::class, 1);
    }

    #[Test]
    public function it_can_reindex_a_model(): void
    {
        $data = [
            'id' => 123,
            'title' => 'My title',
            'body' => 'My body',
        ];

        $article = new Article();
        $article->fill($data);

        $this->elastic->getClient()->shouldReceive('index')
            ->with([
                'id' => 123,
                'index' => 'article',
                'body' => $data,
            ])
            ->once()
            ->andReturn([
                'result' => 'updated',
                '_shards' => ['success' => 1],
                '_version' => 1,
                '_index' => 'article',
            ]);

        $this->assertTrue($article->elastic()->update());
    }

    #[Test]
    public function it_bulk_indexes_models(): void
    {
        Event::fake();
        Elasticsearch::enableEvents();

        $articles = collect([
            new Article([
                'id' => 123,
                'title' => 'My title Article A',
                'body' => 'My body Article A',
            ]),
            new Article([
                'id' => 456,
                'title' => 'My title Article B',
                'body' => 'My body Article B',
            ]),
        ]);

        $this->elastic->getClient()->shouldReceive('bulk')
            ->with([
                'refresh' => true,
                'body' => implode(PHP_EOL, [
                    json_encode(['index' => ['_index' => 'article', '_id' => 123]]),
                    json_encode(['id' => 123, 'title' => 'My title Article A', 'body' => 'My body Article A']),
                    json_encode(['index' => ['_index' => 'article', '_id' => 456]]),
                    json_encode(['id' => 456, 'title' => 'My title Article B', 'body' => 'My body Article B']),
                ]).PHP_EOL,
            ])
            ->once()
            ->andReturn([
                'items' => [
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                    [
                        'index' => [
                            'result' => 'updated',
                            '_shards' => ['success' => 1],
                            '_version' => 1,
                            '_index' => 'article',
                        ],
                    ],
                ],
            ]);

        $this->assertTrue($this->elastic->add($articles));

        Event::assertDispatchedTimes(BulkDocumentsEvent::class, 1);
    }
}
