<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Prefix;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class PrefixTest extends TestCase
{
    /** @test **/
    public function it_builds_prefix_clause()
    {
        $prefix = new Prefix('user', 'ki');

        $this->assertEquals(['prefix' => ['user' => ['value' => 'ki']]], $prefix->toArray());
    }

    /** @test **/
    public function it_builds_prefix_clause_with_rewrite()
    {
        $prefix = new Prefix('user', 'ki', 'constant_score');

        $this->assertEquals(['prefix' => ['user' => [
            'value'   => 'ki',
            'rewrite' => 'constant_score',
        ]]], $prefix->toArray());
    }
}
