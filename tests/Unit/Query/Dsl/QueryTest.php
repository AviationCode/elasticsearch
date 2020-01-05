<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\MustNot;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Should;
use AviationCode\Elasticsearch\Query\Dsl\Query;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class QueryTest extends TestCase
{
    /** @test **/
    public function it_can_add_must()
    {
        $query = new Query();

        $query->must(function (Must $must) {
            $must->simpleQueryString('test must');
        });

        $this->assertEquals([
            'bool' => [
                'must' => [
                    ['simple_query_string' => ['query' => 'test must']],
                ],
            ],
        ], $query->toArray());
    }

    /** @test **/
    public function it_can_add_filter()
    {
        $query = new Query();

        $query->filter(function (Filter $must) {
            $must->simpleQueryString('test filter');
        });

        $this->assertEquals([
            'bool' => [
                'filter' => [
                    ['simple_query_string' => ['query' => 'test filter']],
                ],
            ],
        ], $query->toArray());
    }

    /** @test **/
    public function it_can_add_should()
    {
        $query = new Query();

        $query->should(function (Should $must) {
            $must->simpleQueryString('test should');
        });

        $this->assertEquals([
            'bool' => [
                'should' => [
                    ['simple_query_string' => ['query' => 'test should']],
                ],
            ],
        ], $query->toArray());
    }

    /** @test **/
    public function it_can_add_must_not()
    {
        $query = new Query();

        $query->mustNot(function (MustNot $must) {
            $must->simpleQueryString('test must_not');
        });

        $this->assertEquals([
            'bool' => [
                'must_not' => [
                    ['simple_query_string' => ['query' => 'test must_not']],
                ],
            ],
        ], $query->toArray());
    }
}
