<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchQuery;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MatchQueryTest extends TestCase
{
    #[Test]
    public function it_builds_match_clause()
    {
        $match = new MatchQuery('message', 'this is a test');

        $this->assertEquals([
            'match' => [
                'message' => [
                    'query' => 'this is a test',
                ],
            ],
        ], $match->toArray());
    }

    #[Test]
    public function it_builds_match_clause_with_options()
    {
        $match = new MatchQuery('message', 'this is a test', ['operator' => 'and']);

        $this->assertEquals([
            'match' => [
                'message' => [
                    'query' => 'this is a test',
                    'operator' => 'and',
                ],
            ],
        ], $match->toArray());
    }
}
