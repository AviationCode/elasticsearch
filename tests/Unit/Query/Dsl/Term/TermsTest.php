<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Term;

use AviationCode\Elasticsearch\Query\Dsl\Term\Terms;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class TermsTest extends TestCase
{
    /** @test **/
    public function it_builds_terms()
    {
        $terms = new Terms('user', ['kimchy', 'elasticsearch']);

        $this->assertEquals([
            'terms' => [
                'user' => ['kimchy', 'elasticsearch'],
            ],
        ], $terms->toArray());
    }

    /** @test **/
    public function it_builds_terms_with_boost()
    {
        $terms = new Terms('user', ['kimchy', 'elasticsearch'], 2.0);

        $this->assertEquals([
            'terms' => [
                'user'  => ['kimchy', 'elasticsearch'],
                'boost' => 2.0,
            ],
        ], $terms->toArray());
    }
}
