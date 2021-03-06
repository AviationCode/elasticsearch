<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Ids;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class IdsTest extends TestCase
{
    /** @test **/
    public function it_builds_ids_clause()
    {
        $this->assertEquals(['ids' => ['values' => [1, 2, 3, 4, 5]]], (new Ids([1, 2, 3, 4, 5]))->toArray());
    }
}
