<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model\Aggregations\Bucket;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Composite;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class CompositeTest extends TestCase
{
    #[Test]
    public function it_can_construct_a_composite_instance(): void
    {
        $composite = new Composite(
            [
                'buckets' => [
                    [
                        'key' => [
                            'some_field_name' => 'value1',
                        ],
                        'doc_count' => 10,
                    ],
                    [
                        'key' => [
                            'some_field_name' => 'value2',
                            'some_other_field_name' => 'value2B',
                        ],
                        'doc_count' => 99,
                    ],
                ],
            ]
        );

        $this->assertCount(2, $composite);
        $this->assertSame('value2', $composite->get(1)->key->some_field_name);
        $this->assertSame('value2B', $composite->get(1)->key->some_other_field_name);
        $this->assertSame(10, $composite->get(0)->doc_count);
        $this->assertNull($composite->after_key);
    }

    #[Test]
    public function it_parses_the_after_key(): void
    {
        $composite = new Composite(
            [
                'buckets' => [
                    [
                        'key' => [
                            'field_name' => 'value',
                        ],
                        'doc_count' => 10,
                    ],
                ],
                'after_key' => [
                    'field_name' => 'value',
                ],
            ]
        );

        $this->assertCount(1, $composite);
        $this->assertEquals(10, $composite->get(0)->doc_count);
        $this->assertEquals(['field_name' => 'value'], $composite->after_key);
    }
}