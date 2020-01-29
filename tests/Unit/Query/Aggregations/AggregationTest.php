<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AggregationTest extends TestCase
{
    /** @test **/
    public function it_builds_a_complete_nested_example()
    {
        $aggs = new Aggregation();

        $aggs->terms('users', 'users')
            ->dateHistogram('users.tweets_per_day', 'created_at', '1d');

        $this->assertEquals([
            'users' => [
                'terms' => ['field' => 'users'],
                'aggs' => [
                    'tweets_per_day' => [
                        'date_histogram' => ['field' => 'created_at', 'fixed_interval' => '1d'],
                    ],
                ],
            ],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_value_count_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->valueCount('types_count', 'type');

        $this->assertEquals([
            'types_count' => ['value_count' => ['field' => 'type']],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_cardinality_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->cardinality('type_count', 'type');
        /** With Custom 'precision_threshold' */
        $aggs->cardinality('grade_count', 'grade', ['precision_threshold' => 4000]);
        /**  With 'missing' option */
        $aggs->cardinality('tag_cardinality', 'tag', ['missing' => 'N/A']);

        $this->assertEquals([
            'type_count' => ['cardinality' => ['field' => 'type']],
            'grade_count' => ['cardinality' => ['field' => 'grade', 'precision_threshold' => 4000]],
            'tag_cardinality' => ['cardinality' => ['field' => 'tag', 'missing' => 'N/A']],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_min_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->min('min_price', 'price');
        $aggs->min('min_grade', 'grade', ['missing' => 60]);

        $this->assertEquals([
            'min_price' => ['min' => ['field' => 'price']],
            'min_grade' => ['min' => ['field' => 'grade', 'missing' => 60]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_max_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->max('max_price', 'price');
        $aggs->max('max_grade', 'grade', ['missing' => 75]); // With passing $missing optional parameter

        $this->assertEquals([
            'max_price' => ['max' => ['field' => 'price']],
            'max_grade' => ['max' => ['field' => 'grade', 'missing' => 75]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_stats_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->stats('grades_stats', 'grade');
        $aggs->stats('quantities_stats', 'quantity', ['missing' => 1]);

        $this->assertEquals([
            'grades_stats' => ['stats' => ['field' => 'grade']],
            'quantities_stats' => ['stats' => ['field' => 'quantity', 'missing' => 1]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_an_extended_stats_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->extendedStats('prices_stats', 'price');
        $aggs->extendedStats('grades_stats', 'grade', ['sigma' => 3]);
        $aggs->extendedStats('quantities_stats', 'quantity', ['missing' => 1]);

        $this->assertEquals([
            'prices_stats' => ['extended_stats' => ['field' => 'price']],
            'grades_stats' => ['extended_stats' => ['field' => 'grade', 'sigma' => 3]],
            'quantities_stats' => ['extended_stats' => ['field' => 'quantity', 'missing' => 1]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_an_avg_aggregation()
    {
        $aggs = new Aggregation();

        $aggs->avg('avg_grade', 'grade');
        $aggs->avg('avg_price', 'price', ['missing' => 10]);

        $this->assertEquals([
            'avg_grade' => ['avg' => ['field' => 'grade']],
            'avg_price' => ['avg' => ['field' => 'price', 'missing' => 10]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_builds_a_weighted_avg_aggregation()
    {
        $aggs = new Aggregation();

        $value = ['field' => 'price', 'missing' => 10];
        $weight = ['field' => 'sales_percentage', 'missing' => 0.10];
        $aggs->weightedAvg('weighted_price', $value, $weight);

        $this->assertEquals([
            'weighted_price' => ['weighted_avg' => ['value' => $value, 'weight' => $weight]],
        ], $aggs->toArray());
    }

    /** @test **/
    public function it_throws_exception_when_aggregation_does_not_exist()
    {
        $this->expectException(\BadMethodCallException::class);

        $aggs = new Aggregation();

        $aggs->foobar('types_count', 'foo');

        $this->markSuccessfull();
    }

    /** @test **/
    public function it_throws_exception_when_using_nested_aggregation_before_it_is_defined()
    {
        $this->expectException(\InvalidArgumentException::class);

        $aggs = new Aggregation();

        $aggs->dateHistogram('users.tweets_per_day', 'created_at', '1d')
            ->terms('users', 'users');

        $this->markSuccessfull();
    }

    /** @test **/
    public function it_throws_exception_when_key_is_not_set()
    {
        $this->expectException(\InvalidArgumentException::class);

        $aggs = new Aggregation();

        $aggs->dateHistogram();

        $this->markSuccessfull();
    }
}
