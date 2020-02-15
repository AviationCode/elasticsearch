<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Regexp;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class RegexpTest extends TestCase
{
    /** @test **/
    public function it_builds_simple_regexp()
    {
        $regexp = new Regexp('user', 'k.*y');

        $this->assertEquals(['regexp' => ['user' => ['value' => 'k.*y']]], $regexp->toArray());
    }

    /** @test **/
    public function it_builds_advanced_regexp()
    {
        $regexp = new Regexp('user', 'k.*y', [
            'flags'                   => 'ALL',
            'max_determinized_states' => 10000,
            'rewrite'                 => 'constant_score',
        ]);

        $this->assertEquals(['regexp' => [
            'user' => [
                'value'                   => 'k.*y',
                'flags'                   => 'ALL',
                'max_determinized_states' => 10000,
                'rewrite'                 => 'constant_score',
            ],
        ]], $regexp->toArray());
    }
}
