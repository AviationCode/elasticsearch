<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Prefix;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class PrefixTest extends TestCase
{
    #[Test]
    public function it_builds_prefix_clause(): void
    {
        $prefix = new Prefix('user', 'ki');

        $this->assertEquals(['prefix' => ['user' => ['value' => 'ki']]], $prefix->toArray());
    }

    #[Test]
    public function it_builds_prefix_clause_with_rewrite(): void
    {
        $prefix = new Prefix('user', 'ki', 'constant_score');

        $this->assertEquals(['prefix' => ['user' => [
            'value' => 'ki',
            'rewrite' => 'constant_score',
        ]]], $prefix->toArray());
    }
}
