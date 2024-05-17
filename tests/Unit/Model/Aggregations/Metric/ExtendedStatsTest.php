<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Metric;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Common\Item;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;
use Illuminate\Support\Arr;

class ExtendedStatsTest extends TestCase
{
    #[Test]
    public function it_translates_extended_stats_aggregation()
    {
        $values = [
            'count' => 2,
            'min' => 50.0,
            'max' => 100.0,
            'avg' => 75.0,
            'sum' => 150.0,
            'sum_of_squares' => 12500.0,
            'variance' => 625.0,
            'std_deviation' => 25.0,
            'std_deviation_bounds' => [
                'upper' => 125.0,
                'lower' => 25.0,
            ],
        ];

        $extendedStats = new Item($values);

        foreach (Arr::except($values, 'std_deviation_bounds') as $attribute => $value) {
            $this->assertEquals($value, $extendedStats->$attribute);
        }

        $this->assertEquals(125.0, $extendedStats->std_deviation_bounds->upper);
        $this->assertEquals(25.0, $extendedStats->std_deviation_bounds->lower);
    }
}
