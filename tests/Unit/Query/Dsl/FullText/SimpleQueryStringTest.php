<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\SimpleQueryString;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class SimpleQueryStringTest extends TestCase
{
    #[Test]
    public function it_adds_query_string_clause(): void
    {
        $queryString = new SimpleQueryString('this is a test');

        $this->assertEquals([
            'simple_query_string' => [
                'query' => 'this is a test',
            ],
        ], $queryString->toArray());
    }

    #[Test]
    public function it_adds_query_string_clause_with_options(): void
    {
        $queryString = new SimpleQueryString('this is a test', ['fields' => ['title', 'body']]);

        $this->assertEquals([
            'simple_query_string' => [
                'query' => 'this is a test',
                'fields' => ['title', 'body'],
            ],
        ], $queryString->toArray());
    }
}
