<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\RareTerms;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class RareTermsTest extends TestCase
{
    /** @test **/
    public function it_builds_a_rare_terms_aggregations()
    {
        $rare = new RareTerms('genre');

        $this->assertEquals([
            'rare_terms' => [
                'field' => 'genre',
            ],
        ], $rare->toArray());
    }

    /** @test **/
    public function it_builds_a_rare_terms_aggregations_with_valid_options()
    {
        $rare = new RareTerms('genre', [
            'max_doc_count' => 1,
            'precision' => 0.01,
            'include' => ['swing', 'rock'],
            'exclude' => 'jazz',
            'missing' => 'N/A',
        ]);

        $this->assertEquals([
            'rare_terms' => [
                'field' => 'genre',
                'max_doc_count' => 1,
                'precision' => 0.01,
                'include' => ['swing', 'rock'],
                'exclude' => 'jazz',
                'missing' => 'N/A',
            ],
        ], $rare->toArray());
    }

    /** @test **/
    public function it_builds_a_rare_terms_aggregations_with_invalid_options()
    {
        $rare = new RareTerms('genre', [
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'rare_terms' => [
                'field' => 'genre',
            ],
        ], $rare->toArray());
    }
}
