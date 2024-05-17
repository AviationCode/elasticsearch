<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchPhrasePrefix;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MatchPhrasePrefixTest extends TestCase
{
    #[Test]
    public function it_builds_match_phrase_prefix_query()
    {
        $match = new MatchPhrasePrefix('message', 'quick brown f');

        $this->assertEquals([
            'match_phrase_prefix' => [
                'message' => [
                    'query' => 'quick brown f',
                ],
            ],
        ], $match->toArray());
    }

    #[Test]
    public function it_builds_match_phrase_prefix_query_with_options()
    {
        $match = new MatchPhrasePrefix('message', 'quick brown f', ['max_expansions' => 10]);

        $this->assertEquals([
            'match_phrase_prefix' => [
                'message' => [
                    'query' => 'quick brown f',
                    'max_expansions' => 10,
                ],
            ],
        ], $match->toArray());
    }
}
