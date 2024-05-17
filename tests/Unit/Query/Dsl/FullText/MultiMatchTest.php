<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MultiMatch;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class MultiMatchTest extends TestCase
{
    #[Test]
    public function it_builds_match_phrase_prefix_query(): void
    {
        $match = new MultiMatch(['title', 'message'], 'this is a test');

        $this->assertEquals([
            'multi_match' => [
                'query' => 'this is a test',
                'fields' => ['title', 'message'],
            ],
        ], $match->toArray());
    }

    #[Test]
    public function it_builds_match_phrase_prefix_query_with_options(): void
    {
        $match = new MultiMatch(['title', '*_name'], 'Will Smith', ['type' => 'best_fields']);

        $this->assertEquals([
            'multi_match' => [
                'query' => 'Will Smith',
                'fields' => ['title', '*_name'],
                'type' => 'best_fields',
            ],
        ], $match->toArray());
    }
}
