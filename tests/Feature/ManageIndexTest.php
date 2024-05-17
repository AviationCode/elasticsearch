<?php

namespace AviationCode\Elasticsearch\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Tests\Feature\TestModels\Article;

class ManageIndexTest extends TestCase
{
    #[Test]
    public function it_creates_index_from_model(): void
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
