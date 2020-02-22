<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Metric\PercentileRanks;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PercentileRanksTest extends TestCase
{
    /** @test **/
    public function it_builds_a_percentile_ranks_aggregation()
    {
        $percentiles = new PercentileRanks('load_time', [500, 600]);

        $this->assertEquals([
            'percentile_ranks' => [
                'field' => 'load_time',
                'values' => [500, 600],
            ]
        ], $percentiles->toArray());
    }

    /** @test **/
    public function it_builds_a_percentile_ranks_aggregation_with_options()
    {
        $percentiles = new PercentileRanks('load_time', [500, 600], [
            'script' => ['lang' => 'painless'],
            'hdr' => ['number_of_significant_value_digits' => 3],
            'missing' => 10,
        ]);

        $this->assertEquals([
            'percentile_ranks' => [
                'field' => 'load_time',
                'values' => [500, 600],
                'script' => ['lang' => 'painless'],
                'hdr' => ['number_of_significant_value_digits' => 3],
                'missing' => 10,
            ]
        ], $percentiles->toArray());
    }

    /** @test **/
    public function it_builds_a_percentile_ranks_aggregation_with_invalid_options()
    {
        $percentiles = new PercentileRanks('load_time', [500, 600], ['invalid' => 'value']);

        $this->assertEquals([
            'percentile_ranks' => [
                'field' => 'load_time',
                'values' => [500, 600],
            ]
        ], $percentiles->toArray());
    }
}
