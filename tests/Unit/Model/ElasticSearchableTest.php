<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model;

use AviationCode\Elasticsearch\Model\ElasticCollection;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ElasticSearchableTest extends TestCase
{
    /** @test **/
    public function it_builds_eloquent_model_from_elastic_results()
    {
        $article = (new ArticleTestModel())->newFromElasticBuilder([
            '_id' => 123,
            '_source' => [
                'title' => 'article title',
                'body' => 'Article body',
            ],
        ]);

        $this->assertEquals(123, $article->id);
        $this->assertFalse($article->wasRecentlyCreated);
        $this->assertEquals('article title', $article->title);
        $this->assertEquals('Article body', $article->body);
    }

    /** @test **/
    public function the_indexed_model_defaults_to_toArray()
    {
        $article = new ArticleTestModel();
        $article->fill([
            'title' => 'test title',
            'body' => 'test body',
        ]);

        $this->assertEquals([
            'title' => 'test title',
            'body' => 'test body',
        ], $article->toSearchable());
    }

    /** @test **/
    public function it_uses_elasticcollection()
    {
        $collection = (new ArticleTestModel())->newCollection();

        $this->assertInstanceOf(ElasticCollection::class, $collection);
    }
}
