<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\SerialDiff;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SerialDiffTest extends TestCase
{
    /** @test **/
    public function it_builds_serial_diff_aggregation()
    {
        $serial = new SerialDiff('the_sum');

        $this->assertEquals([
            'serial_diff' => [
                'buckets_path' => 'the_sum'
            ],
        ], $serial->toArray());
    }

    /** @test **/
    public function it_builds_serial_diff_aggregation_with_options()
    {
        $serial = new SerialDiff('the_sum', 7, 'epoch_second', SerialDiff::SKIP);

        $this->assertEquals([
            'serial_diff' => [
                'buckets_path' => 'the_sum',
                'lag' => 7,
                'gap_policy' => 'skip',
                'format' => 'epoch_second'
            ],
        ], $serial->toArray());
    }
}
