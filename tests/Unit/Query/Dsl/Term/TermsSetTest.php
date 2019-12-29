<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\TermsSet;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermsSetTest extends TestCase
{
    /** @test **/
    public function it_builds_terms_set()
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

    /** @test **/
    public function it_builds_terms_set_with_options()
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
