<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\Fuzzy;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FuzzyTest extends TestCase
{
    #[Test]
    public function it_builds_simple_fuzzy_clause(): void
    {
        $fuzzy = new Fuzzy('user', 'ki');

        $this->assertEquals(['fuzzy' => ['user' => ['value' => 'ki']]], $fuzzy->toArray());
    }

    #[Test]
    public function it_builds_advanced_fuzzy_clause(): void
    {
        $fuzzy = new Fuzzy('user', 'ki', [
            'fuzziness' => Fuzzy::FUZZINESS_AUTO,
            'max_expansions' => 50,
            'prefix_length' => 0,
            'transposition' => true,
            'rewrite' => Fuzzy::REWRITE_CONSTANT,
        ]);

        $this->assertEquals([
            'fuzzy' => [
                'user' => [
                    'value' => 'ki',
                    'fuzziness' => 'AUTO',
                    'max_expansions' => 50,
                    'prefix_length' => 0,
                    'transposition' => true,
                    'rewrite' => 'constant_score',
                ],
            ],
        ], $fuzzy->toArray());
    }
}
