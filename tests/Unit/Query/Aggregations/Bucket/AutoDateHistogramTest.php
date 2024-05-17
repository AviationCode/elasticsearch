<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\AutoDateHistogram;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class AutoDateHistogramTest extends TestCase
{
    #[Test]
    public function it_builds_a_auto_date_histogram_aggregation(): void
    {
        $histogram = new AutoDateHistogram('date');

        $this->assertEquals([
            'auto_date_histogram' => [
                'field' => 'date',
            ],
        ], $histogram->toArray());
    }

    #[Test]
    public function it_builds_a_auto_date_histogram_aggregation_with_options(): void
    {
        $histogram = new AutoDateHistogram('date', [
            'buckets' => 10,
            'format' => 'yyyy-MM-dd',
            'time_zone' => '-01:00',
            'minimum_interval' => 'minute',
            'missing' => '2000/01/01',
        ]);

        $this->assertEquals([
            'auto_date_histogram' => [
                'field' => 'date',
                'buckets' => 10,
                'format' => 'yyyy-MM-dd',
                'time_zone' => '-01:00',
                'minimum_interval' => 'minute',
                'missing' => '2000/01/01',
            ],
        ], $histogram->toArray());
    }

    #[Test]
    public function it_builds_a_auto_date_histogram_aggregation_with_invalid_options(): void
    {
        $histogram = new AutoDateHistogram('date', [
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'auto_date_histogram' => [
                'field' => 'date',
            ],
        ], $histogram->toArray());
    }
}
