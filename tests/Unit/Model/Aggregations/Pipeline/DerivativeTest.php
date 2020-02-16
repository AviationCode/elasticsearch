<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Model\Aggregations\Pipeline\Derivative;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class DerivativeTest extends TestCase
{
    /** @test **/
    public function it_translates_derivative()
    {
        $derivative = new Derivative(['value' => -0.25]);

        $this->assertSame(-0.25, $derivative->value);
    }

    /** @test **/
    public function it_translates_derivative_normalized_value()
    {
        $derivative = new Derivative(['value' => -0.25, 'normalized_value' => -0.45]);

        $this->assertSame(-0.25, $derivative->value);
        $this->assertSame(-0.45, $derivative->normalized_value);
    }
}
