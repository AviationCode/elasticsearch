<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use PHPUnit\Framework\Attributes\Test;
use AviationCode\Elasticsearch\Query\Dsl\Term\TermsSet;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

final class TermsSetTest extends TestCase
{
    #[Test]
    public function it_builds_terms_set(): void
    {
        $termsSet = new TermsSet('programming_languages', ['php', 'java']);

        $this->assertEquals([
            'terms_set' => [
                'programming_languages' => [
                    'terms' => ['php', 'java'],
                ],
            ],
        ], $termsSet->toArray());
    }

    #[Test]
    public function it_builds_terms_set_with_options(): void
    {
        $termsSet = new TermsSet('programming_languages', ['php', 'java'], [
            'minimum_should_match_field' => 'required_matches',
        ]);

        $this->assertEquals([
            'terms_set' => [
                'programming_languages' => [
                    'terms' => ['php', 'java'],
                    'minimum_should_match_field' => 'required_matches',
                ],
            ],
        ], $termsSet->toArray());
    }
}
