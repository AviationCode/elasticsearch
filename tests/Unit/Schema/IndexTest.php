<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Schema;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Exceptions\BadRequestException;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use AviationCode\Elasticsearch\Model\ElasticsearchModel;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Namespaces\IndicesNamespace;
use InvalidArgumentException;

class IndexTest extends TestCase
{
    /** @var Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface Moc */
    protected $client;

    /** @var IndicesNamespace|\Mockery\LegacyMockInterface|\Mockery\MockInterface */
    protected $indices;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = \Mockery::mock(Client::class);
        $this->indices = \Mockery::mock(IndicesNamespace::class);
        $this->client->shouldReceive('indices')->andReturn($this->indices);
    }

    protected function getSchema($model = null)
    {
        $search = new Elasticsearch($model);
        $search->setClient($this->client);

        return $search->index();
    }

    /** @test **/
    public function it_checks_if_index_from_given_parameter()
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'index_does_not_exist'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema()->exists('index_does_not_exist'));
    }

    /** @test **/
    public function it_checks_index_exists_from_a_model_class()
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema(ArticleTestModel::class)->exists());
    }

    /** @test **/
    public function it_checks_index_exists_from_a_model_instance()
    {
        $this->indices->shouldReceive('exists')
            ->with(['index' => 'article_test_model'])
            ->once()
            ->andReturn(false);

        $this->assertFalse($this->getSchema(new ArticleTestModel())->exists());
    }

    /** @test **/
    public function it_throws_exception_when_no_model_or_index_is_given()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getSchema()->exists();
    }

    /** @test **/
    public function it_creates_an_index_given_as_param()
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

    /** @test **/
    public function it_creates_index_using_model_and_configures_known_fields()
    {
        $this->indices->shouldReceive('create')
            ->with(['index' => 'my_index'])
            ->once()
            ->andReturn([
                'acknowledged' => true,
                'shards_acknowledged' => true,
                'index' => 'my_index',
            ]);

        $this->indices->shouldReceive('putMapping')
            ->with([
                'index' => 'my_index',
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
                    ],
                ],
            ])
            ->once()
            ->andReturn(['acknowledged' => true]);

        $this->getSchema(ArticleTestModel::class)->create('my_index');

        $this->markSuccessfull();
    }

    /** @test **/
    public function it_creating_a_duplicate_index_throws_an_exception()
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

    /** @test **/
    public function it_deletes_index()
    {
        $this->indices->shouldReceive('delete')
            ->with(['index' => 'my_index'])
            ->once()
            ->andReturn(['acknowledged' => true]);

        $this->getSchema()->delete('my_index');

        $this->markSuccessfull();
    }

    /** @test **/
    public function it_throws_exception_if_index_does_not_exist()
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

    /** @test **/
    public function it_throws_exception_if_no_model_or_index_is_provided()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getSchema()->delete();
    }

    /** @test **/
    public function it_puts_index_mapping()
    {
        $this->indices->shouldReceive('putMapping')
            ->with([
                'index' => 'my-index',
                'body' => [
                    'properties' => [
                        'field1' => [
                            'type' => 'keyword',
                        ],
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
}

class ArticleTestModel extends ElasticsearchModel
{
}
