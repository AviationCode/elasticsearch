<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Metric\MedianAbsoluteDeviation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class MedianAbsoluteDeviationTest extends TestCase
{
    #[Test]
    public function it_builds_a_median_absolute_deviation_aggregation(): void
    {
        $median = new MedianAbsoluteDeviation('rating');

        $this->assertEquals([
            'median_absolute_deviation' => [
                'field' => 'rating',
            ]
        ], $median->toArray());
    }

    #[Test]
    public function it_builds_a_median_absolute_deviation_aggregation_with_options(): void
    {
        $median = new MedianAbsoluteDeviation('rating', [
            'field' => 'rating',
            'script' => ['lang' => 'painless'],
            'missing' => 10,
            'compression' => 100,
        ]);

        $this->assertEquals([
            'median_absolute_deviation' => [
                'field' => 'rating',
                'script' => ['lang' => 'painless'],
                'missing' => 10,
                'compression' => 100,
            ]
        ], $median->toArray());
    }

    #[Test]
    public function it_builds_a_median_absolute_deviation_aggregation_with_invalid_options(): void
    {
        $median = new MedianAbsoluteDeviation('rating', ['invalid' => 'value']);

        $this->assertEquals([
            'median_absolute_deviation' => [
                'field' => 'rating',
            ]
        ], $median->toArray());
    }
}
