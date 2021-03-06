<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use AviationCode\Elasticsearch\Query\Dsl\FullText\SimpleQueryString;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SimpleQueryStringTest extends TestCase
{
    /** @test **/
    public function it_adds_query_string_clause()
    {
        $queryString = new SimpleQueryString('this is a test');

        $this->assertEquals([
            'simple_query_string' => [
                'query' => 'this is a test',
            ],
        ], $queryString->toArray());
    }

    /** @test **/
    public function it_adds_query_string_clause_with_options()
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
