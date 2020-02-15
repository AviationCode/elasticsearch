<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\FullText;

use AviationCode\Elasticsearch\Query\Dsl\FullText\QueryString;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class QueryStringTest extends TestCase
{
    /** @test **/
    public function it_adds_query_string_clause()
    {
        $queryString = new QueryString('this is a test');

        $this->assertEquals([
            'query_string' => [
                'query' => 'this is a test',
            ],
        ], $queryString->toArray());
    }

    /** @test **/
    public function it_adds_query_string_clause_with_options()
    {
        $queryString = new QueryString('this is a test', ['default_field' => 'body']);

        $this->assertEquals([
            'query_string' => [
                'query'         => 'this is a test',
                'default_field' => 'body',
            ],
        ], $queryString->toArray());
    }
}
