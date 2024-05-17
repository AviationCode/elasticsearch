<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;
use Elasticsearch\Client;

final class CountTest extends TestCase
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
    public function it_can_count_the_documents_without_filtering(): void
    {
        $this->client
            ->shouldReceive('count')
            ->with(['index' => 'article', 'body' => []])
            ->andReturn(
                [
                    'count' => 122,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(122, $this->elastic->query(Article::class)->count());
    }

    #[Test]
    public function it_can_count_the_documents_without_an_eloquent_model(): void
    {
        $this->client
            ->shouldReceive('count')
            ->with(['index' => 'article', 'body' => []])
            ->andReturn(
                [
                    'count' => 6,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(6, $this->elastic->query('article')->count());
    }

    #[Test]
    public function it_can_count_the_matching_documents_with_filter(): void
    {
        $this->client
            ->shouldReceive('count')
            ->with(
                [
                    'index' => 'article',
                    'body' => [
                        'query' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'exists' => [
                                            'field' => 'published_at',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                [
                    'count' => 12,
                    '_shards' => [
                        'total' => 1,
                        'successful' => 1,
                        'skipped' => 0,
                        'failed' => 0,
                    ],
                ]
            );

        $this->assertSame(
            12,
            $this->elastic
                ->query('article')
                ->must(
                    function (Must $must) {
                        $must->exists('published_at');
                    }
                )
                ->count()
        );
    }
}
