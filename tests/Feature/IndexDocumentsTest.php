<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\Events\DocumentCreatedEvent;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Facades\Event;

class IndexDocumentsTest extends TestCase
{
    /** @test **/
    public function it_can_index_a_model()
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

    /** @test **/
    public function it_can_index_a_model_given_as_param()
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

    /** @test **/
    public function it_throws_exception_if_no_model_is_given()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->elastic = Elasticsearch::fake();

        $this->elastic->add();
    }

    /** @test **/
    public function it_throws_exception_if_the_index_does_not_exist()
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

    /** @test **/
    public function it_fires_document_created_event()
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

    /** @test **/
    public function it_can_reindex_a_model()
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
}
