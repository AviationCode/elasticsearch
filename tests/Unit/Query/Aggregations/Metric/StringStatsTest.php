<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Metric\StringStats;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class StringStatsTest extends TestCase
{
    /** @test **/
    public function it_builds_a_stats_aggregation()
    {
        $stats = new StringStats('message.keyword');

        $this->assertEquals([
            'string_stats' => [
                'field' => 'message.keyword',
            ]
        ], $stats->toArray());
    }

    /** @test **/
    public function it_builds_a_stats_aggregation_with_options()
    {
        $stats = new StringStats('message.keyword', [
            'show_distribution' => true,
            'script' => ['id' => 'my_script'],
            'missing' => '[empty message]',
        ]);

        $this->assertEquals([
            'string_stats' => [
                'field' => 'message.keyword',
                'show_distribution' => true,
                'script' => ['id' => 'my_script'],
                'missing' => '[empty message]',
            ]
        ], $stats->toArray());
    }

    /** @test **/
    public function it_builds_a_stats_aggregation_with_invalid_options()
    {
        $stats = new StringStats('message.keyword', [
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'string_stats' => [
                'field' => 'message.keyword',
            ]
        ], $stats->toArray());
    }
}
