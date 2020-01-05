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
            ->dateHistorygram('users.tweets_per_day', 'created_at', '1d');

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
            'aggs' => [
                'types_count' => ['value_count' => ['field' => 'type']],
            ],
        ], $aggs->toArray());
    }
}
