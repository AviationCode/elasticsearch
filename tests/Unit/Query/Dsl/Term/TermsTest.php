<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Terms;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class TermsTest extends TestCase
{
    #[Test]
    public function it_builds_terms(): void
    {
        $terms = new Terms('user', ['kimchy', 'elasticsearch']);

        $this->assertEquals([
            'terms' => [
                'user' => ['kimchy', 'elasticsearch'],
            ],
        ], $terms->toArray());
    }

    #[Test]
    public function it_builds_terms_with_boost(): void
    {
        $terms = new Terms('user', ['kimchy', 'elasticsearch'], 2.0);

        $this->assertEquals([
            'terms' => [
                'user' => ['kimchy', 'elasticsearch'],
                'boost' => 2.0,
            ],
        ], $terms->toArray());
    }
}
