<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Wildcard;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class WildcardTest extends TestCase
{
    #[Test]
    public function it_builds_wildcard()
    {
        $wildcard = new Wildcard('user', 'ki.*y');

        $this->assertEquals([
            'wildcard' => [
                'user' => [
                    'value' => 'ki.*y',
                ],
            ],
        ], $wildcard->toArray());
    }

    #[Test]
    public function it_builds_wildcard_with_boost()
    {
        $wildcard = new Wildcard('user', 'ki.*y', [
            'boost' => 2.0,
        ]);

        $this->assertEquals([
            'wildcard' => [
                'user' => [
                    'value' => 'ki.*y',
                    'boost' => 2.0,
                ],
            ],
        ], $wildcard->toArray());
    }

    #[Test]
    public function it_builds_wildcard_with_rewrite()
    {
        $wildcard = new Wildcard('user', 'ki.*y', [
            'rewrite' => 'constant_score',
        ]);

        $this->assertEquals([
            'wildcard' => [
                'user' => [
                    'value' => 'ki.*y',
                    'rewrite' => 'constant_score',
                ],
            ],
        ], $wildcard->toArray());
    }
}
