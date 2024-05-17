<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Schema;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Exceptions\BadRequestException;
use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use AviationCode\Elasticsearch\Model\ElasticsearchModel;
use AviationCode\Elasticsearch\Model\Index;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Namespaces\CatNamespace;
use Elasticsearch\Namespaces\IndicesNamespace;
use InvalidArgumentException;

class IndexTest extends TestCase
{
    /** @var Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface Moc */
    protected $client;

    /** @var IndicesNamespace|\Mockery\LegacyMockInterface|\Mockery\MockInterface */
    protected $indices;

    /** @var CatNamespace|\Mockery\LegacyMockInterface|\Mockery\MockInterface */
    protected $cat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = \Mockery::mock(Client::class);
        $this->indices = \Mockery::mock(IndicesNamespace::class);
        $this->cat = \Mockery::mock(CatNamespace::class);
        $this->client->shouldReceive('indices')->andReturn($this->indices);
        $this->client->shouldReceive('cat')->andReturn($this->cat);
    }

    protected function getSchema($model = null)
    {
        $search = new Elasticsearch($model);
        $search->setClient($this->client);

        return $search->index();
    }

    #[Test]
    public function it_checks_if_index_from_given_parameter(): void
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'index_does_not_exist'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema()->exists('index_does_not_exist'));
    }

    #[Test]
    public function it_checks_index_exists_from_a_model_class(): void
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema(ArticleTestModel::class)->exists());
    }

    #[Test]
    public function it_checks_index_exists_from_a_model_instance(): void
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema(new ArticleTestModel())->exists());
    }

    #[Test]
    public function it_throws_exception_when_no_model_or_index_is_given(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getSchema()->exists();
    }

    #[Test]
    public function it_creates_an_index_given_as_param(): void
    {
        $this->indices->shouldReceive('create')
            ->with(['index' => 'my_index'])
            ->once()
            ->andReturn([
                'acknowledged' => true,
                'shards_acknowledged' => true,
                'index' => 'my_index',
            ]);

        $this->getSchema()->create('my_index');

        $this->markSuccessfull();
    }

    #[Test]
    public function it_creates_index_using_model_and_configures_known_fields(): void
    {
        $this->indices->shouldReceive('create')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn([
                'acknowledged' => true,
                'shards_acknowledged' => true,
                'index' => 'my_index',
            ]);

        $this->indices->shouldReceive('putMapping')
            ->with([
                'index' => 'article_test_model',
                'body' => [
                    'properties' => [
                        'created_at' => [
                            'type' => 'date',
                            'ignore_malformed' => true,
                            'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
                        ],
                        'updated_at' => [
                            'type' => 'date',
                            'ignore_malformed' => true,
                            'format' => 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd',
                        ],
                        'id' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ])
            ->once()
            ->andReturn(['acknowledged' => true]);

        $this->getSchema(ArticleTestModel::class)->create();

        $this->markSuccessfull();
    }

    #[Test]
    public function it_throws_exceptions_when_creating_index_when_model_is_already_provided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getSchema(ArticleTestModel::class)->create('my_index');
    }

    #[Test]
    public function it_creating_a_duplicate_index_throws_an_exception(): void
    {
        $this->indices->shouldReceive('create')
            ->with(['index' => 'my_index'])
            ->once()
            ->andThrow(new BadRequest400Exception(json_encode([
                'error' => [
                    'root_cause' => [
                        'type' => 'resource_already_exists_exception',
                        'reason' => 'index [my_index/1A-w2u9mT3KJUEdoSBH6GA] already exists',
                        'index_uuid' => 'w2u9mT3KJUEdoSBH6GA',
                        'index' => 'my_index',
                    ],
                    'type' => 'resource_already_exists_exception',
                    'reason' => 'index [my_index/1A-w2u9mT3KJUEdoSBH6GA] already exists',
                    'index_uuid' => 'w2u9mT3KJUEdoSBH6GA',
                    'index' => 'my_index',
                ],
                'status' => 400,
            ])));

        $this->expectException(BadRequestException::class);

        $this->getSchema()->create('my_index');
    }

    #[Test]
    public function it_deletes_index(): void
    {
        $this->indices->shouldReceive('delete')
            ->with(['index' => 'my_index'])
            ->once()
            ->andReturn(['acknowledged' => true]);

        $this->getSchema()->delete('my_index');

        $this->markSuccessfull();
    }

    #[Test]
    public function it_throws_exception_if_index_does_not_exist(): void
    {
        $this->indices->shouldReceive('delete')
            ->with(['index' => 'my_index'])
            ->once()
            ->andThrow(new Missing404Exception(json_encode([
                'error' => [
                    'root_cause' => [
                        'type' => 'index_not_found_exception',
                        'reason' => 'no such index [my_index]',
                        'resource.type' => 'index_or_alias',
                        'resource.id' => 'my_index',
                        'index' => 'my_index',
                    ],
                    'type' => 'index_not_found_exception',
                    'reason' => 'no such index [my_index]',
                    'resource.type' => 'index_or_alias',
                    'resource.id' => 'my_index',
                    'index' => 'my_index',
                ],
                'status' => 404,
            ])));

        $this->expectException(IndexNotFoundException::class);
        $this->getSchema()->delete('my_index');
    }

    #[Test]
    public function it_throws_exception_if_no_model_or_index_is_provided(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getSchema()->delete();
    }

    #[Test]
    public function it_puts_index_mapping(): void
    {
        $this->indices->shouldReceive('putMapping')
            ->with([
                'index' => 'my-index',
                'body' => [
                    'properties' => [
                        'field1' => ['type' => 'keyword'],
                    ],
                ],
            ])
            ->once()
            ->andReturn(['acknowledged' => true]);

        $this->getSchema()->putMapping([
            'field1' => [
                'type' => 'keyword',
            ],
        ], 'my-index');

        $this->markSuccessfull();
    }

    #[Test]
    public function it_puts_invalid_mapping(): void
    {
        $this->expectException(BaseElasticsearchException::class);

        $this->indices->shouldReceive('putMapping')
            ->with([
                'index' => 'my-index',
                'body' => [
                    'properties' => [
                        'field1' => ['type' => 'invalid-type'],
                    ],
                ],
            ])
            ->once()
            ->andThrows(new BadRequest400Exception('Parsing Exception'));

        $this->getSchema()->putMapping([
            'field1' => [
                'type' => 'invalid-type',
            ],
        ], 'my-index');
    }

    #[Test]
    public function it_can_request_index_info(): void
    {
        $info = [
            'mappings' => ['properties' => ['city' => ['type' => 'keyword']]],
            'settings' => [
                'index' => [
                    'creation_date' => '1580500912933',
                    'number_of_shards' => '1',
                    'number_of_replicas' => '1',
                ]
            ]
        ];

        $this->indices->shouldReceive('get')
            ->with(['index' => 'my-index'])
            ->once()
            ->andReturn(['my-index' => $info]);

        $result = $this->getSchema()->info('my-index');

        $this->assertEquals($info, $result);
    }

    #[Test]
    public function it_can_request_index_info_from_a_model(): void
    {
        $info = [
            'mappings' => ['properties' => ['city' => ['type' => 'keyword']]],
            'settings' => [
                'index' => [
                    'creation_date' => '1580500912933',
                    'number_of_shards' => '1',
                    'number_of_replicas' => '1',
                ]
            ]
        ];

        $this->indices->shouldReceive('get')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn(['article_test_model' => $info]);

        $result = $this->getSchema(ArticleTestModel::class)->info();

        $this->assertEquals($info, $result);
    }

    #[Test]
    public function it_can_request_index_info_handle_exceptions(): void
    {
        $this->indices->shouldReceive('get')
            ->with(['index' => 'my_index'])
            ->once()
            ->andThrow(new Missing404Exception(json_encode([
                'error' => [
                    'root_cause' => [
                        'type' => 'index_not_found_exception',
                        'reason' => 'no such index [my_index]',
                        'resource.type' => 'index_or_alias',
                        'resource.id' => 'my_index',
                        'index' => 'my_index',
                    ],
                    'type' => 'index_not_found_exception',
                    'reason' => 'no such index [my_index]',
                    'resource.type' => 'index_or_alias',
                    'resource.id' => 'my_index',
                    'index' => 'my_index',
                ],
                'status' => 404,
            ])));

        $this->expectException(IndexNotFoundException::class);

        $this->getSchema()->info('my_index');
    }

    #[Test]
    public function it_can_request_a_list_of_indices(): void
    {
        $this->cat->shouldReceive('indices')->andReturn([
            [
                'health' => 'yellow',
                'status' => 'open',
                'index' => 'addresses',
                'uuid' => 'Kv0cga10RiCSCXg8BXQgjA',
                'pri' => '1',
                'rep' => '1',
                'docs.count' => '56516672',
                'docs.deleted' => '0',
                'store.size' => '7.5gb',
                'pri.store.size' => '7.5gb',
            ],
            [
                'health' => 'green',
                'status' => 'open',
                'index' => '.kibana_task_manager_1',
                'uuid' => 'tz5cJ_20S_-Q18WCKc10aw',
                'pri' => '1',
                'rep' => '0',
                'docs.count' => '2',
                'docs.deleted' => '0',
                'store.size' => '12.4kb',
                'pri.store.size' => '12.4kb',
          ],
        ]);

        $indices = $this->getSchema()->list();

        $this->assertCount(2, $indices);
        $this->assertInstanceOf(Index::class, $indices[0]);
        $this->assertInstanceOf(Index::class, $indices[1]);
    }
}

class ArticleTestModel extends ElasticsearchModel
{
}
