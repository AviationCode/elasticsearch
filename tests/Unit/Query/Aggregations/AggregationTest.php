<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class AggregationTest extends TestCase
{
    #[Test]
    public function it_builds_a_complete_nested_example(): void
    {
        $aggs = new Aggregation();

        $aggs->terms('users', 'users')
            ->dateHistogram('tweets_per_day', 'created_at', '1d');

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

        $aggs = new Aggregation();
        $aggs->terms('users', 'users');
        $aggs->dateHistogram('users.tweets_per_day', 'created_at', '1d');

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

    #[Test]
    public function it_throws_exception_when_aggregation_does_not_exist(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $aggs = new Aggregation();

        $aggs->foobar('types_count', 'foo');

        $this->markSuccessfull();
    }

    #[Test]
    public function it_throws_exception_when_using_nested_aggregation_before_it_is_defined(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $aggs = new Aggregation();

        $aggs->dateHistogram('users.tweets_per_day', 'created_at', '1d');
        $aggs->terms('users', 'users');

        $this->markSuccessfull();
    }

    #[Test]
    public function it_throws_exception_when_key_is_not_set(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $aggs = new Aggregation();

        $aggs->dateHistogram();

        $this->markSuccessfull();
    }
}
