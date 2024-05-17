<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Ids;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class IdsTest extends TestCase
{
    #[Test]
    public function it_builds_ids_clause(): void
    {
        $this->assertEquals(['ids' => ['values' => [1, 2, 3, 4, 5]]], (new Ids([1, 2, 3, 4, 5]))->toArray());
    }
}
