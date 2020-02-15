<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use AviationCode\Elasticsearch\Query\Dsl\FullText\Match;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MatchTest extends TestCase
{
    /** @test **/
    public function it_builds_match_clause()
    {
        $match = new Match('message', 'this is a test');

        $this->assertEquals([
            'match' => [
                'message' => [
                    'query' => 'this is a test',
                ],
            ],
        ], $match->toArray());
    }

    /** @test **/
    public function it_builds_match_clause_with_options()
    {
        $match = new Match('message', 'this is a test', ['operator' => 'and']);

        $this->assertEquals([
            'match' => [
                'message' => [
                    'query'    => 'this is a test',
                    'operator' => 'and',
                ],
            ],
        ], $match->toArray());
    }
}
