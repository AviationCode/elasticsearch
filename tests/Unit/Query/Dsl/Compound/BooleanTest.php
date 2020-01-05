<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Compound;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Must;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\MustNot;
use AviationCode\Elasticsearch\Query\Dsl\Boolean\Should;
use AviationCode\Elasticsearch\Query\Dsl\Compound\Boolean;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class BooleanTest extends TestCase
{
    /** @test **/
    public function it_builds_all_possible_query_objects()
    {
        $boolean = new Boolean();
        $boolean->must(function (Must $must) {
            $this->assertInstanceOf(Must::class, $must);

            return $must->simpleQueryString('test must');
        });
        $boolean->should(function (Should $should) {
            $this->assertInstanceOf(Should::class, $should);

            return $should->simpleQueryString('test should');
        });
        $boolean->filter(function (Filter $filter) {
            $this->assertInstanceOf(Filter::class, $filter);

            return $filter->simpleQueryString('test filter');
        });
        $boolean->mustNot(function (MustNot $mustNot) {
            $this->assertInstanceOf(MustNot::class, $mustNot);

            return $mustNot->simpleQueryString('test must_not');
        });

        $this->assertEquals([
            'must' => [['simple_query_string' => ['query' => 'test must']]],
            'should' => [['simple_query_string' => ['query' => 'test should']]],
            'filter' => [['simple_query_string' => ['query' => 'test filter']]],
            'must_not' => [['simple_query_string' => ['query' => 'test must_not']]],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_throws_error_when_calling_invalid_driver()
    {
        $this->expectException(\BadMethodCallException::class);

        $boolean = new Boolean();
        $boolean->foobar(function () {});

        $this->markSuccessfull();
    }

}
