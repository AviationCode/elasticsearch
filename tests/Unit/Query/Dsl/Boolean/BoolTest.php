<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Boolean\Boolean;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoBoundingBox;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoDistance;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoPolygon;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoShape;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

abstract class BoolTest extends TestCase
{
    abstract protected function newBooleanClass(): Boolean;

    /** @test **/
    public function empty_filter_clause()
    {
        $boolean = $this->newBooleanClass();

        $this->assertEquals([], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_multiple_clauses()
    {
        $boolean = $this->newBooleanClass();

        $boolean->term('language', 'php');
        $boolean->range('age', '>=', '20');

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
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_exists_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->exists('language');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'exists' => [
                    'field' => 'language',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_fuzzy_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->fuzzy('user', 'ki');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'fuzzy' => [
                    'user' => ['value' => 'ki'],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_fuzzy_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->fuzzy('user', 'ki', ['transposition' => true]);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'fuzzy' => [
                    'user' => [
                        'value' => 'ki',
                        'transposition' => true,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_ids_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->ids([1, 2]);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            ['ids' => ['values' => [1, 2]]],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_prefix_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->prefix('user', 'ki');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            ['prefix' => ['user' => ['value' => 'ki']]],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_prefix_query_with_rewrite()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->prefix('user', 'ki', 'constant_score');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'prefix' => [
                    'user' => [
                        'value' => 'ki',
                        'rewrite' => 'constant_score',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_range_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->range('created_at', 'lte', '2019-12-20', ['boost' => 2.0]);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'range' => [
                    'created_at' => [
                        'lte' => '2019-12-20',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_regexp_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->regexp('user', 'ki.*');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'regexp' => [
                    'user' => [
                        'value' => 'ki.*',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_regexp_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->regexp('user', 'ki.*', ['flag' => 'ALL']);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'regexp' => [
                    'user' => [
                        'value' => 'ki.*',
                        'flag' => 'ALL',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_terms_set_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->termsSet('languages', ['php', 'java']);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'terms_set' => [
                    'languages' => [
                        'terms' => ['php', 'java'],
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_terms_set_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->termsSet('languages', ['php', 'java'], ['minimum_should_match_field' => 'required_matches']);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'terms_set' => [
                    'languages' => [
                        'terms' => ['php', 'java'],
                        'minimum_should_match_field' => 'required_matches',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_term_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->term('status', 'published');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'term' => [
                    'status' => [
                        'value' => 'published',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_term_query_with_boost()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->term('status', 'published', 2.0);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'term' => [
                    'status' => [
                        'value' => 'published',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_wildcard_query()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->wildcard('user', 'ki.*y');

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'wildcard' => [
                    'user' => [
                        'value' => 'ki.*y',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_wildcard_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $result = $boolean->wildcard('user', 'ki.*y', ['boost' => 2.0]);

        $this->assertEquals($boolean, $result);
        $this->assertEquals([
            [
                'wildcard' => [
                    'user' => [
                        'value' => 'ki.*y',
                        'boost' => 2.0,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->match('message', 'quick brown f');

        $this->assertEquals([
            [
                'match' => [
                    'message' => [
                        'query' => 'quick brown f',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_bool_prefix_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchBoolPrefix('message', 'quick brown f');

        $this->assertEquals([
            [
                'match_bool_prefix' => [
                    'message' => [
                        'query' => 'quick brown f',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_bool_prefix_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchBoolPrefix('message', 'quick brown f', ['analyzer' => 'keyword']);

        $this->assertEquals([
            [
                'match_bool_prefix' => [
                    'message' => [
                        'query' => 'quick brown f',
                        'analyzer' => 'keyword',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchPhrase('message', 'this is a test');

        $this->assertEquals([
            [
                'match_phrase' => [
                    'message' => [
                        'query' => 'this is a test',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchPhrase('message', 'this is a test', ['analyzer' => 'my_analyzer']);

        $this->assertEquals([
            [
                'match_phrase' => [
                    'message' => [
                        'query' => 'this is a test',
                        'analyzer' => 'my_analyzer',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_prefix_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchPhrasePrefix('message', 'this is a test');

        $this->assertEquals([
            [
                'match_phrase_prefix' => [
                    'message' => [
                        'query' => 'this is a test',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_match_phrase_prefix_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->matchPhrasePrefix('message', 'this is a test', ['analyzer' => 'my_analyzer']);

        $this->assertEquals([
            [
                'match_phrase_prefix' => [
                    'message' => [
                        'query' => 'this is a test',
                        'analyzer' => 'my_analyzer',
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_multi_match_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->multiMatch(['title', 'message'], 'this is a test');

        $this->assertEquals([
            [
                'multi_match' => [
                    'query' => 'this is a test',
                    'fields' => ['title', 'message'],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_multi_match_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->multiMatch(['title', '*_name'], 'this is a test', ['type' => 'best_fields']);

        $this->assertEquals([
            [
                'multi_match' => [
                    'query' => 'this is a test',
                    'fields' => ['title', '*_name'],
                    'type' => 'best_fields',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_query_string_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->queryString('this is a test');

        $this->assertEquals([
            [
                'query_string' => [
                    'query' => 'this is a test',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_query_string_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->queryString('this is a test', ['default_field' => 'body']);

        $this->assertEquals([
            [
                'query_string' => [
                    'query' => 'this is a test',
                    'default_field' => 'body',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_simple_query_string_query()
    {
        $boolean = $this->newBooleanClass();

        $boolean->simpleQueryString('this is a test');

        $this->assertEquals([
            [
                'simple_query_string' => [
                    'query' => 'this is a test',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_simple_query_string_query_with_options()
    {
        $boolean = $this->newBooleanClass();

        $boolean->simpleQueryString('this is a test', ['fields' => ['title', 'body']]);

        $this->assertEquals([
            [
                'simple_query_string' => [
                    'query' => 'this is a test',
                    'fields' => ['title', 'body'],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_geo_shape()
    {
        $boolean = $this->newBooleanClass();

        $boolean->geoShape('location', ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'], GeoShape::INDEXED_SHAPE, true);

        $this->assertEquals([
            [
                'geo_shape' => [
                    'location' => [
                        'indexed_shape' => ['index' => 'shapes', 'id' => 'abc', 'path' => 'location'],
                        'ignored_unmapped' => true,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_geo_polygon()
    {
        $boolean = $this->newBooleanClass();

        $boolean->geoPolygon('location', [
            ['lat' => 51.1, 'lon' => 4.1],
            ['lat' => 52.2, 'lon' => 5.2],
            ['lat' => 53.3, 'lon' => 6.3],
        ], GeoPolygon::STRICT, true);

        $this->assertEquals([
            [
                'geo_polygon' => [
                    'location' => [
                        'points' => [
                            ['lat' => 51.1, 'lon' => 4.1],
                            ['lat' => 52.2, 'lon' => 5.2],
                            ['lat' => 53.3, 'lon' => 6.3],
                        ],
                        'validation_method' => 'strict',
                        'ignore_unmapped' => true,
                    ],
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_geo_distance()
    {
        $boolean = $this->newBooleanClass();

        $boolean->geoDistance('location', 40, -70, 123, GeoDistance::M, ['distance_type' => GeoDistance::ARC]);

        $this->assertEquals([
            [
                'geo_distance' => [
                    'distance' => '123m',
                    'location' => ['lat' => 40, 'lon' => -70],
                    'distance_type' => 'arc',
                ],
            ],
        ], $boolean->toArray());
    }

    /** @test **/
    public function it_adds_geo_bounding_box()
    {
        $boolean = $this->newBooleanClass();

        $boolean->geoBoundingBox('location', ['lat' => 40.73, 'lon' => -74.1], ['lat' => 40.01, 'lon' => -71.12], [
            'validation_method' => GeoboundingBox::IGNORE_MALFORMED,
            'type' => GeoBoundingBox::MEMORY,
            'ignore_unmapped' => true,
        ]);

        $this->assertEquals([
            [
                'geo_bounding_box' => [
                    'location' => [
                        'top_left' => ['lat' => 40.73, 'lon' => -74.1],
                        'bottom_right' => ['lat' => 40.01, 'lon' => -71.12],
                    ],
                    'validation_method' => 'ignore_malformed',
                    'type' => 'memory',
                    'ignore_unmapped' => true,
                ],
            ],
        ], $boolean->toArray());
    }
}
