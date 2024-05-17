<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Metric\Percentiles;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PercentilesTest extends TestCase
{
    #[Test]
    public function it_builds_a_percentiles_aggregation(): void
    {
        $percentiles = new Percentiles('load_time');

        $this->assertEquals([
            'percentiles' => [
                'field' => 'load_time',
            ]
        ], $percentiles->toArray());
    }

    #[Test]
    public function it_builds_a_percentiles_aggregation_with_options(): void
    {
        $percentiles = new Percentiles('load_time', [
            'script' => ['lang' => 'painless'],
            'tdigest' => ['compression' => 200],
            'hdr' => ['number_of_significant_value_digits' => 3],
            'missing' => 10,
        ]);

        $this->assertEquals([
            'percentiles' => [
                'field' => 'load_time',
                'script' => ['lang' => 'painless'],
                'tdigest' => ['compression' => 200],
                'hdr' => ['number_of_significant_value_digits' => 3],
                'missing' => 10,
            ]
        ], $percentiles->toArray());
    }

    #[Test]
    public function it_builds_a_percentiles_aggregation_with_invalid_options(): void
    {
        $percentiles = new Percentiles('load_time', ['invalid' => 'value']);

        $this->assertEquals([
            'percentiles' => [
                'field' => 'load_time',
            ]
        ], $percentiles->toArray());
    }
}
