<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermTest extends TestCase
{
    #[Test]
    public function it_builds_term_clause(): void
    {
        $term = new Term('status', 'published');

        $this->assertEquals([
            'term' => ['status' => ['value' => 'published']],
        ], $term->toArray());
    }

    #[Test]
    public function it_builds_term_clause_with_boost(): void
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
