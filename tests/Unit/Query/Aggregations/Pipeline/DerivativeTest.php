<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Pipeline;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Pipeline\Derivative;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class DerivativeTest extends TestCase
{
    #[Test]
    public function it_builds_derivative_aggregation(): void
    {
        $derivative = new Derivative('the_sum');

        $this->assertEquals([
            'derivative' => [
                'buckets_path' => 'the_sum',
            ],
        ], $derivative->toArray());
    }

    #[Test]
    public function it_builds_derivative_aggregation_gap_policy(): void
    {
        $derivative = new Derivative('the_sum', Derivative::GAP_INSERT_ZEROS);

        $this->assertEquals([
            'derivative' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'insert_zeros',
            ],
        ], $derivative->toArray());

        $derivative = new Derivative('the_sum', Derivative::GAP_SKIP);

        $this->assertEquals([
            'derivative' => [
                'buckets_path' => 'the_sum',
                'gap_policy' => 'skip',
            ],
        ], $derivative->toArray());
    }

    #[Test]
    public function it_builds_derivative_aggregation_format(): void
    {
        $derivative = new Derivative('the_sum', null, '000.00');

        $this->assertEquals([
            'derivative' => [
                'buckets_path' => 'the_sum',
                'format' => '000.00',
            ],
        ], $derivative->toArray());
    }

    #[Test]
    public function it_builds_derivative_aggregation_unit(): void
    {
        $derivative = new Derivative('the_sum', null, null, 'day');

        $this->assertEquals([
            'derivative' => [
                'buckets_path' => 'the_sum',
                'unit' => 'day'
            ],
        ], $derivative->toArray());
    }
}
