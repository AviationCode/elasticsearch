<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;

class ManageIndexTest extends TestCase
{
    protected function elasticRequest($method)
    {
        Elasticsearch::fake()->indicesClient
            ->shouldReceive($method)
            ->once()
            ->andReturn(['acknowledged' => true]);
    }

    /** @test **/
    public function it_creates_index_from_model()
    {
        Elasticsearch::fake()->indicesClient
            ->shouldReceive('create')
            ->once()
            ->andReturn(['acknowledged' => true]);

        $article = new Article();

        $article->elastic()->index()->create();
    }
}
