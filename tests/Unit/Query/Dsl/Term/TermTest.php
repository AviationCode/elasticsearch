<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermTest extends TestCase
{
    /** @test **/
    public function it_builds_term_clause()
    {
        $term = new Term('status', 'published');

        $this->assertEquals([
            'term' => ['status' => ['value' => 'published']],
        ], $term->toArray());
    }

    /** @test **/
    public function it_builds_term_clause_with_boost()
    {
        $term = new Term('status', 'published', 2.0);

        $this->assertEquals([
            'term' => ['status' => [
                'value' => 'published',
                'boost' => 2.0,
            ]],
        ], $term->toArray());
    }
}
