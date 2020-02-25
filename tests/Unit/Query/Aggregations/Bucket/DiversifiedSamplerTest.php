<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DiversifiedSampler;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class DiversifiedSamplerTest extends TestCase
{
    /** @test **/
    public function it_builds_a_sampler_aggregation()
    {
        $sampler = new DiversifiedSampler();

        $this->assertEquals(['diversified_sampler' => new \stdClass()], $sampler->toArray());
    }

    /** @test **/
    public function it_builds_a_sampler_aggregation_with_field_and_shard()
    {
        $sampler = new DiversifiedSampler(100, 'author');

        $this->assertEquals(['diversified_sampler' => ['field' => 'author', 'shard_size' => 100]], $sampler->toArray());
    }

    /** @test **/
    public function it_builds_a_sampler_aggregation_with_field_and_shard_and_max_doc_per_value()
    {
        $sampler = new DiversifiedSampler(100, 'author', 3);

        $this->assertEquals(['diversified_sampler' => [
            'field' => 'author',
            'shard_size' => 100,
            'max_docs_per_value' => 3,
        ]], $sampler->toArray());
    }

    /** @test **/
    public function it_builds_a_sampler_aggregation_with_shard()
    {
        $sampler = new DiversifiedSampler(100);

        $this->assertEquals(['diversified_sampler' => ['shard_size' => 100]], $sampler->toArray());
    }
}
