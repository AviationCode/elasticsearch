<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchPhrase;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MatchPhraseTest extends TestCase
{
    /** @test **/
    public function it_matches_phrase_query()
    {
        $match = new MatchPhrase('message', 'this is a test');

        $this->assertEquals([
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                ],
            ],
        ], $match->toArray());
    }

    /** @test **/
    public function it_matches_phrase_query_with_options()
    {
        $match = new MatchPhrase('message', 'this is a test', ['analyzer' => 'my_analyzer']);

        $this->assertEquals([
            'match_phrase' => [
                'message' => [
                    'query' => 'this is a test',
                    'analyzer' => 'my_analyzer',
                ],
            ],
        ], $match->toArray());
    }
}
