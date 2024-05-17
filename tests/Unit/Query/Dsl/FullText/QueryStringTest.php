<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\FullText\QueryString;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class QueryStringTest extends TestCase
{
    #[Test]
    public function it_adds_query_string_clause(): void
    {
        $queryString = new QueryString('this is a test');

        $this->assertEquals([
            'query_string' => [
                'query' => 'this is a test',
            ],
        ], $queryString->toArray());
    }

    #[Test]
    public function it_adds_query_string_clause_with_options(): void
    {
        $queryString = new QueryString('this is a test', ['default_field' => 'body']);

        $this->assertEquals([
            'query_string' => [
                'query' => 'this is a test',
                'default_field' => 'body',
            ],
        ], $queryString->toArray());
    }
}
