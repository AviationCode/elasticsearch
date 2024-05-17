<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Model;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Model\Index;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class IndexTest extends TestCase
{
    #[Test]
    public function it_builds_index(): void
    {
        $elasticResult = [
            'health' => 'yellow',
            'status' => 'open',
            'index' => 'addresses',
            'uuid' => 'Kv0cga10RiCSCXg8BXQgjA',
            'pri' => '1',
            'rep' => '1',
            'docs.count' => '56516672',
            'docs.deleted' => '0',
            'store.size' => '7.5gb',
            'pri.store.size' => '7.5gb',
        ];

        $index = new Index($elasticResult);

        $this->assertEquals('yellow', $index->health);
        $this->assertEquals('open', $index->status);
        $this->assertEquals('addresses', $index->index);
        $this->assertEquals('Kv0cga10RiCSCXg8BXQgjA', $index->uuid);
        $this->assertEquals(1, $index->pri);
        $this->assertEquals(1, $index->rep);
        $this->assertEquals(56516672, $index->docs_count);
        $this->assertEquals(0, $index->docs_deleted);
        $this->assertEquals('7.5gb', $index->store_size);
        $this->assertEquals('7.5gb', $index->pri_store_size);
    }

    #[Test]
    public function it_marks_indices_with_a_dot_as_internal(): void
    {
        $this->assertFalse((new Index(['index' => 'addresses']))->isInternal());
        $this->assertTrue((new Index(['index' => '.kibana']))->isInternal());
    }

    #[Test]
    public function it_replaces_dot_syntax_with_underscores(): void
    {
        $index = new Index(['docs.count' => 100]);

        $this->assertEquals(100, $index->docs_count);
    }
}
