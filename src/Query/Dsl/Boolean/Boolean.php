<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\FullText\Match;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchBoolPrefix;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchPhrase;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MatchPhrasePrefix;
use AviationCode\Elasticsearch\Query\Dsl\FullText\MultiMatch;
use AviationCode\Elasticsearch\Query\Dsl\FullText\QueryString;
use AviationCode\Elasticsearch\Query\Dsl\FullText\SimpleQueryString;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoBoundingBox;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoDistance;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoPolygon;
use AviationCode\Elasticsearch\Query\Dsl\Geo\GeoShape;
use AviationCode\Elasticsearch\Query\Dsl\Term\Exists;
use AviationCode\Elasticsearch\Query\Dsl\Term\Fuzzy;
use AviationCode\Elasticsearch\Query\Dsl\Term\Ids;
use AviationCode\Elasticsearch\Query\Dsl\Term\Prefix;
use AviationCode\Elasticsearch\Query\Dsl\Term\Range;
use AviationCode\Elasticsearch\Query\Dsl\Term\Regexp;
use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use AviationCode\Elasticsearch\Query\Dsl\Term\TermsSet;
use AviationCode\Elasticsearch\Query\Dsl\Term\Wildcard;

class Boolean
{
    /**
     * Clauses to apply.
     *
     * @var array
     */
    protected $clauses = [];

    /**
     * Returns documents that contain any indexed value for a field.
     *
     * @param string $field
     *
     * @return $this
     */
    public function exists(string $field): self
    {
        $this->clauses[] = new Exists($field);

        return $this;
    }

    /**
     * Returns documents that contain terms similar to the search term.
     * Elasticsearch measures similarity, or fuzziness, using a Levenshtein edit distance.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function fuzzy(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new Fuzzy($field, $value, $options);

        return $this;
    }

    /**
     * Returns documents based on their document IDs.
     *
     * @param array $ids
     *
     * @return $this
     */
    public function ids(array $ids): self
    {
        $this->clauses[] = new Ids($ids);

        return $this;
    }

    /**
     * Returns documents that contain a specific prefix in a provided field.
     *
     * @param string $field
     * @param $value
     * @param string|null $rewrite
     *
     * @return $this
     */
    public function prefix(string $field, $value, ?string $rewrite = null): self
    {
        $this->clauses[] = new Prefix($field, $value, $rewrite);

        return $this;
    }

    /**
     * Returns documents that contain terms within a provided range.
     *
     * @param $field
     * @param string $operator
     * @param null $date
     * @param array $options
     *
     * @return $this
     */
    public function range($field, $operator = 'gte', $date = null, array $options = []): self
    {
        $this->clauses[] = new Range($field, $operator, $date, $options);

        return $this;
    }

    /**
     * Returns documents that contain terms matching a regular expression.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function regexp(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new Regexp($field, $value, $options);

        return $this;
    }

    /**
     * Returns documents that contain an exact term in a provided field.
     *
     * @param string $field
     * @param string $value
     * @param float|null $boost
     *
     * @return $this
     */
    public function term(string $field, string $value, ?float $boost = null): self
    {
        $this->clauses[] = new Term($field, $value, $boost);

        return $this;
    }

    /**
     * Returns documents that contain a minimum number of exact terms in a provided field.
     * You can define the minimum number of matching terms using a field or script.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function termsSet(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new TermsSet($field, $value, $options);

        return $this;
    }

    /**
     * Returns documents that contain terms matching a wildcard pattern.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function wildcard(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new Wildcard($field, $value, $options);

        return $this;
    }

    /**
     * Returns documents that match a provided text, number, date or boolean value.
     * The provided text is analyzed before matching.
     *
     * @param string $key
     * @param $query
     * @param array $options
     * @return $this
     */
    public function match(string $key, $query, array $options = []): self
    {
        $this->clauses[] = new Match($key, $query, $options);

        return $this;
    }

    /**
     * Creates a bool query that matches each term as a term query,
     * except for the last term, which is matched as a prefix query.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function matchBoolPrefix(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new MatchBoolPrefix($field, $value, $options);

        return $this;
    }

    /**
     * Like the match query but used for matching exact phrases or word proximity matches.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function matchPhrase(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new MatchPhrase($field, $value, $options);

        return $this;
    }

    /**
     * Like the match_phrase query, but does a wildcard search on the final word.
     *
     * @param string $field
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function matchPhrasePrefix(string $field, $value, array $options = []): self
    {
        $this->clauses[] = new MatchPhrasePrefix($field, $value, $options);

        return $this;
    }

    /**
     * The multi-field version of the match query.
     *
     * @param array $fields
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function multiMatch(array $fields, $value, array $options = []): self
    {
        $this->clauses[] = new MultiMatch($fields, $value, $options);

        return $this;
    }

    /**
     * Supports the compact Lucene query string syntax, allowing you to specify AND|OR|NOT
     * conditions and multi-field search within a single query string.
     * For expert users only.
     *
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function queryString($value, array $options = []): self
    {
        $this->clauses[] = new QueryString($value, $options);

        return $this;
    }

    /**
     * A simpler, more robust version of the query_string syntax suitable for exposing directly to users.
     *
     * @param $value
     * @param array $options
     *
     * @return $this
     */
    public function simpleQueryString($value, array $options = []): self
    {
        $this->clauses[] = new SimpleQueryString($value, $options);

        return $this;
    }

    /**
     * Finds documents with geo-shapes which either intersect, are contained by,
     * or do not intersect with the specified geo-shape.
     *
     * @param string $field
     * @param array $shape
     * @param string|null $relation
     * @param bool $unmapped
     *
     * @return $this
     */
    public function geoShape(string $field, array $shape, ?string $relation = null, bool $unmapped = false): self
    {
        $this->clauses[] = new GeoShape($field, $shape, $relation, $unmapped);

        return $this;
    }

    /**
     * Find documents with geo-points within the specified polygon.
     *
     * @param string $field
     * @param array $points
     * @param string|null $validationMethod
     * @param bool $unmapped
     *
     * @return $this
     */
    public function geoPolygon(string $field, array $points, ?string $validationMethod, bool $unmapped): self
    {
        $this->clauses[] = new GeoPolygon($field, $points, $validationMethod, $unmapped);

        return $this;
    }

    /**
     * Finds documents with geo-points within the specified distance of a central point.
     *
     * @param string $field
     * @param $lat
     * @param $lon
     * @param null $distance
     * @param null $unit
     * @param array|null $options
     *
     * @return $this
     */
    public function geoDistance(string $field, $lat, $lon, $distance = null, $unit = null, ?array $options = null): self
    {
        $this->clauses[] = new GeoDistance($field, $lat, $lon, $distance, $unit, $options);

        return $this;
    }

    /**
     * Finds documents with geo-points that fall into the specified rectangle.
     *
     * @param string $field
     * @param $topLeft
     * @param null $bottomRight
     * @param array|null $options
     *
     * @return $this
     */
    public function geoBoundingBox(string $field, $topLeft, $bottomRight = null, ?array $options = null): self
    {
        $this->clauses[] = new GeoBoundingBox($field, $topLeft, $bottomRight, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_map(function ($clause) {
            return $clause->toArray();
        }, $this->clauses);
    }
}
