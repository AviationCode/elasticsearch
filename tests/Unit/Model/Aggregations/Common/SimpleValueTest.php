<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Pipeline;

use AviationCode\Elasticsearch\Model\Aggregations\Common\SimpleValue;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SimpleValueTest extends TestCase
{
    /** @test **/
    public function it_translates_derivative()
    {
        $simpleValue = new SimpleValue(['value' => -0.25]);

        $this->assertSame(-0.25, $simpleValue->value);
    }
}
