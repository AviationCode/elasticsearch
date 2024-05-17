<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchBoolPrefix;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class MatchBoolPrefixTest extends TestCase
{
    #[Test]
    public function it_builds_match_bool_prefix(): void
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

    #[Test]
    public function it_builds_match_bool_prefix_with_options(): void
    {
        $matchBoolPrefix = new MatchBoolPrefix('message', 'quick brown f', ['analyzer' => 'keyword']);

        $this->assertEquals([
            'match_bool_prefix' => [
                'message' => [
                    'query' => 'quick brown f',
                    'analyzer' => 'keyword',
                ],
            ],
        ], $matchBoolPrefix->toArray());
    }
}
