<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Filter;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class FilterTest extends TestCase
{
    /** @test **/
    public function empty_filter_clause()
    {
        $filter = new Filter();

        $this->assertEquals([], $filter->toArray());
    }

    /** @test **/
    public function it_adds_multiple_clauses()
    {
        $filter = new Filter();

        $filter->term('language', 'php');
        $filter->range('age', '>=', '20');

        $this->assertEquals([
            [
                'term' => [
                    'language' => [
                        'value' => 'php',
                    ],
                ],
            ],
            [
                'range' => [
                    'age' => [
                        'gte' => '20',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_exists_query()
    {
        $filter = new Filter();

        $result = $filter->exists('language');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'exists' => [
                    'field' => 'language',
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_fuzzy_query()
    {
        $filter = new Filter();

        $result = $filter->fuzzy('user', 'ki');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'fuzzy' => [
                    'user' => ['value' => 'ki'],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_fuzzy_query_with_options()
    {
        $filter = new Filter();

        $result = $filter->fuzzy('user', 'ki', ['transposition' => true]);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'fuzzy' => [
                    'user' => [
                        'value' => 'ki',
                        'transposition' => true,
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_ids_query()
    {
        $filter = new Filter();

        $result = $filter->ids([1, 2]);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            ['ids' => ['values' => [1, 2]]],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_prefix_query()
    {
        $filter = new Filter();

        $result = $filter->prefix('user', 'ki');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            ['prefix' => ['user' => ['value' => 'ki']]],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_prefix_query_with_rewrite()
    {
        $filter = new Filter();

        $result = $filter->prefix('user', 'ki', 'constant_score');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'prefix' => [
                    'user' => [
                        'value' => 'ki',
                        'rewrite' => 'constant_score',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_range_query()
    {
        $filter = new Filter();

        $result = $filter->range('created_at', 'lte', '2019-12-20', ['boost' => 2.0]);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'range' => [
                    'created_at' => [
                        'lte' => '2019-12-20',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_regexp_query()
    {
        $filter = new Filter();

        $result = $filter->regexp('user', 'ki.*');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'regexp' => [
                    'user' => [
                        'value' => 'ki.*',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_regexp_query_with_options()
    {
        $filter = new Filter();

        $result = $filter->regexp('user', 'ki.*', ['flag' => 'ALL']);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'regexp' => [
                    'user' => [
                        'value' => 'ki.*',
                        'flag' => 'ALL',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_terms_set_query()
    {
        $filter = new Filter();

        $result = $filter->termsSet('languages', ['php', 'java']);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'terms_set' => [
                    'languages' => [
                        'terms' => ['php', 'java'],
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_terms_set_query_with_options()
    {
        $filter = new Filter();

        $result = $filter->termsSet('languages', ['php', 'java'], ['minimum_should_match_field' => 'required_matches']);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'terms_set' => [
                    'languages' => [
                        'terms' => ['php', 'java'],
                        'minimum_should_match_field' => 'required_matches',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_term_query()
    {
        $filter = new Filter();

        $result = $filter->term('status', 'published');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'term' => [
                    'status' => [
                        'value' => 'published',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_term_query_with_boost()
    {
        $filter = new Filter();

        $result = $filter->term('status', 'published', 2.0);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'term' => [
                    'status' => [
                        'value' => 'published',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_wildcard_query()
    {
        $filter = new Filter();

        $result = $filter->wildcard('user', 'ki.*y');

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'wildcard' => [
                    'user' => [
                        'value' => 'ki.*y',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_wildcard_query_with_options()
    {
        $filter = new Filter();

        $result = $filter->wildcard('user', 'ki.*y', ['boost' => 2.0]);

        $this->assertEquals($filter, $result);
        $this->assertEquals([
            [
                'wildcard' => [
                    'user' => [
                        'value' => 'ki.*y',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_match_bool_prefix_query()
    {
        $filter = new Filter();

        $filter->matchBoolPrefix('message', 'quick brown f');

        $this->assertEquals([
            [
                'match_bool_prefix' => [
                    'message' => [
                        'query' => 'quick brown f',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_match_bool_prefix_query_with_options()
    {
        $filter = new Filter();

        $filter->matchBoolPrefix('message', 'quick brown f', ['analyzer' => 'keyword']);

        $this->assertEquals([
            [
                'match_bool_prefix' => [
                    'message' => [
                        'query' => 'quick brown f',
                        'analyzer' => 'keyword',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_query()
    {
        $filter = new Filter();

        $filter->matchPhrase('message', 'this is a test');

        $this->assertEquals([
            [
                'match_phrase' => [
                    'message' => [
                        'query' => 'this is a test',
                    ],
                ],
            ],
        ], $filter->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_query_with_options()
    {
        $filter = new Filter();

        $filter->matchPhrase('message', 'this is a test', ['analyzer' => 'my_analyzer']);

        $this->assertEquals([
            [
                'match_phrase' => [
                    'message' => [
                        'query' => 'this is a test',
                        'analyzer' => 'my_analyzer',
                    ],
                ],
            ],
        ], $filter->toArray());
    }
}
