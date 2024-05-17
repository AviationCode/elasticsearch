<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class GeoCentroidTest extends TestCase
{
    #[Test]
    public function it_builds_a_geo_centroid_aggregation(): void
    {
        $aggs = new Aggregation();

        $aggs->geoCentroid('centroid', 'location');

        $this->assertEquals([
            'centroid' => ['geo_centroid' => ['field' => 'location']],
        ], $aggs->toArray());
    }
}
