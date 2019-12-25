<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Exists;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class ExistsTest extends TestCase
{
    /** @test **/
    public function it_builds_exist_clause()
    {
        $exists = new Exists('user');

        $this->assertEquals(['exists' => ['field' => 'user']], $exists->toArray());
    }
}
