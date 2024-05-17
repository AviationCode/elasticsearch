<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\Sampler;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class SamplerTest extends TestCase
{
    #[Test]
    public function it_builds_a_sampler_aggregation()
    {
        $sampler = new Sampler();

        $this->assertEquals(['sampler' => new \stdClass()], $sampler->toArray());
    }

    #[Test]
    public function it_builds_a_sampler_aggregation_with_shard_size()
    {
        $sampler = new Sampler(100);

        $this->assertEquals(['sampler' => ['shard_size' => 100]], $sampler->toArray());
    }
}
