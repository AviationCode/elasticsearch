<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\SerialDiff;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SerialDiffTest extends TestCase
{
    #[Test]
    public function it_builds_serial_diff_aggregation(): void
    {
        $serial = new SerialDiff('the_sum');

        $this->assertEquals([
            'serial_diff' => [
                'buckets_path' => 'the_sum'
            ],
        ], $serial->toArray());
    }

    #[Test]
    public function it_builds_serial_diff_aggregation_with_options(): void
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
