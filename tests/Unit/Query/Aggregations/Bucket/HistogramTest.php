<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Histogram;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class HistogramTest extends TestCase
{
    #[Test]
    public function it_builds_histogram_aggregation(): void
    {
        $histogram = new Histogram('price', 50);

        $this->assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 50,
            ],
        ], $histogram->toArray());
    }

    #[Test]
    public function it_builds_histogram_aggregation_with_valid_options(): void
    {
        $histogram = new Histogram('price', 50, [
            'order' => ['key' => 'desc'],
            'offset' => 100,
            'missing' => 0,
        ]);

        $this->assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 50,
                'order' => ['key' => 'desc'],
                'offset' => 100,
                'missing' => 0,
            ],
        ], $histogram->toArray());
    }

    #[Test]
    public function it_builds_histogram_aggregation_with_invalid_options(): void
    {
        $histogram = new Histogram('price', 50, [
            'invalid_options'
        ]);

        $this->assertEquals([
            'histogram' => [
                'field' => 'price',
                'interval' => 50,
            ],
        ], $histogram->toArray());
    }
}
