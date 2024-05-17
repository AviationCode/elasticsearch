<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\DiversifiedSampler;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class DiversifiedSamplerTest extends TestCase
{
    #[Test]
    public function it_builds_a_sampler_aggregation(): void
    {
        $sampler = new DiversifiedSampler();

        $this->assertEquals(['diversified_sampler' => new \stdClass()], $sampler->toArray());
    }

    #[Test]
    public function it_builds_a_sampler_aggregation_with_field_and_shard(): void
    {
        $sampler = new DiversifiedSampler(100, 'author');

        $this->assertEquals(['diversified_sampler' => ['field' => 'author', 'shard_size' => 100]], $sampler->toArray());
    }

    #[Test]
    public function it_builds_a_sampler_aggregation_with_field_and_shard_and_max_doc_per_value(): void
    {
        $sampler = new DiversifiedSampler(100, 'author', 3);

        $this->assertEquals(['diversified_sampler' => [
            'field' => 'author',
            'shard_size' => 100,
            'max_docs_per_value' => 3,
        ]], $sampler->toArray());
    }

    #[Test]
    public function it_builds_a_sampler_aggregation_with_shard(): void
    {
        $sampler = new DiversifiedSampler(100);

        $this->assertEquals(['diversified_sampler' => ['shard_size' => 100]], $sampler->toArray());
    }
}
