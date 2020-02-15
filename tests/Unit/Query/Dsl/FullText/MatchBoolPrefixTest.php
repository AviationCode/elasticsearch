<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchBoolPrefix;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class MatchBoolPrefixTest extends TestCase
{
    /** @test **/
    public function it_builds_match_bool_prefix()
    {
        $matchBoolPrefix = new MatchBoolPrefix('message', 'quick brown f');

        $this->assertEquals([
            'match_bool_prefix' => [
                'message' => [
                    'query' => 'quick brown f',
                ],
            ],
        ], $matchBoolPrefix->toArray());
    }

    /** @test **/
    public function it_builds_match_bool_prefix_with_options()
    {
        $matchBoolPrefix = new MatchBoolPrefix('message', 'quick brown f', ['analyzer' => 'keyword']);

        $this->assertEquals([
            'match_bool_prefix' => [
                'message' => [
                    'query'    => 'quick brown f',
                    'analyzer' => 'keyword',
                ],
            ],
        ], $matchBoolPrefix->toArray());
    }
}
