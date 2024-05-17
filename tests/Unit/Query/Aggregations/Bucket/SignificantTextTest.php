<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Aggregations\Bucket\SignificantText;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class SignificantTextTest extends TestCase
{
    #[Test]
    public function it_builds_significant_text_aggregation(): void
    {
        $significant = new SignificantText('content');

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
            ],
        ], $significant->toArray());
    }

    #[Test]
    public function it_builds_significant_text_aggregation_with_valid_options(): void
    {
        $significant = new SignificantText('content', [
            'filter_duplicate_text' => true,
            'source_fields' => ['content', 'title'],
        ]);

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
                'filter_duplicate_text' => true,
                'source_fields' => ['content', 'title'],
            ],
        ], $significant->toArray());
    }

    #[Test]
    public function it_builds_significant_text_aggregation_with_invalid_options(): void
    {
        $significant = new SignificantText('content', [
            'invalid' => 'option',
        ]);

        $this->assertEquals([
            'significant_text' => [
                'field' => 'content',
            ],
        ], $significant->toArray());
    }
}
