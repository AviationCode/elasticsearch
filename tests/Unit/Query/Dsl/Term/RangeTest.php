<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Range;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class RangeTest extends TestCase
{
    /** @test **/
    public function it_builds_a_simple_range()
    {
        $range = new Range('age', '>=', 20);

        $this->assertEquals([
            'range' => [
                'age' => [
                    'gte' => 20,
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function it_builds_range_using_callback()
    {
        $range = new Range('age', function (Range $range) {
            return $range->gte(20);
        });

        $this->assertEquals([
            'range' => [
                'age' => [
                    'gte' => 20,
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function optionally_range_can_include_options()
    {
        $range = new Range('age', function (Range $range) {
            return $range->gte(20);
        }, ['boost' => 2.0]);

        $this->assertEquals([
            'range' => [
                'age' => [
                    'gte' => 20,
                    'boost' => 2.0,
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function optionally_range_can_include_simple_options()
    {
        $range = new Range('age', 'gte', 20, ['boost' => 2.0]);

        $this->assertEquals([
            'range' => [
                'age' => [
                    'gte' => 20,
                    'boost' => 2.0,
                ],
            ],
        ], $range->toArray());
    }

    /** @test **/
    public function it_can_build_inclusive_range()
    {
        $expected = ['range' => ['age' => [
           'gte' => 1,
           'lte' => 10,
       ]]];

        $range = new Range('age', function (Range $range) {
            return $range->gte(1)->lte(10);
        });

        $this->assertEquals($expected, $range->toArray());
    }

    /** @test **/
    public function it_can_build_exclusive_range()
    {
        $expected = ['range' => ['age' => [
            'gt' => 1,
            'lt' => 10,
        ]]];

        $range = new Range('age', function (Range $range) {
            return $range->gt(1)->lt(10);
        });

        $this->assertEquals($expected, $range->toArray());
    }

    /** @test **/
    public function it_builds_gt_range()
    {
        $expected = ['range' => ['age' => [
            'gt' => 10,
        ]]];

        $this->assertEquals($expected, (new Range('age', 'gt', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', '>', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', function (Range $range) {
            return $range->gt(10);
        }))->toArray());
    }

    /** @test **/
    public function it_builds_gte_range()
    {
        $expected = ['range' => ['age' => [
            'gte' => 10,
        ]]];

        $this->assertEquals($expected, (new Range('age', 'gte', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', '>=', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', function (Range $range) {
            return $range->gte(10);
        }))->toArray());
    }

    /** @test **/
    public function it_builds_lt_range()
    {
        $expected = ['range' => ['age' => [
            'lt' => 10,
        ]]];

        $this->assertEquals($expected, (new Range('age', 'lt', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', '<', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', function (Range $range) {
            return $range->lt(10);
        }))->toArray());
    }

    /** @test **/
    public function it_builds_lte_range()
    {
        $expected = ['range' => ['age' => [
            'lte' => 10,
        ]]];

        $this->assertEquals($expected, (new Range('age', 'lte', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', '<=', 10))->toArray());
        $this->assertEquals($expected, (new Range('age', function (Range $range) {
            return $range->lte(10);
        }))->toArray());
    }

    /** @test **/
    public function combining_lt_and_lte_throws_exception()
    {
        try {
            new Range('age', function (Range $range) {
                $range->lt(10)->lte(9);
            });

            $this->fail('Expected InvalidArgumentException non throw');
        } catch (\InvalidArgumentException $exception) {
        }

        try {
            new Range('age', function (Range $range) {
                $range->lte(10)->lt(9);
            });

            $this->fail('Expected InvalidArgumentException non throw');
        } catch (\InvalidArgumentException $exception) {
        }

        $this->markSuccessfull();
    }

    /** @test **/
    public function combining_gt_and_gte_throws_exception()
    {
        try {
            new Range('age', function (Range $range) {
                $range->gt(10)->gte(9);
            });

            $this->fail('Expected InvalidArgumentException non throw');
        } catch (\InvalidArgumentException $exception) {
        }

        try {
            new Range('age', function (Range $range) {
                $range->gte(10)->gt(9);
            });

            $this->fail('Expected InvalidArgumentException non throw');
        } catch (\InvalidArgumentException $exception) {
        }

        $this->markSuccessfull();
    }
}
