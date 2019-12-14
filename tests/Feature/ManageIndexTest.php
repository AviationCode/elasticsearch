<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;

class ManageIndexTest extends TestCase
{
    /** @test **/
    public function it_creates_index_from_model()
    {
        $elastic = Elasticsearch::fake();
        $elastic->indicesClient
            ->shouldReceive('create')
            ->once()
            ->andReturn(['acknowledged' => true]);
        $elastic->indicesClient
            ->shouldReceive('putMapping')
            ->once()
            ->andReturn(['acknowledged' => true]);

        $article = new Article();

        $article->elastic()->index()->create();
    }
}
