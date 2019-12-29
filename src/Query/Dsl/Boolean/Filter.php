<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Boolean;

use AviationCode\Elasticsearch\Query\Dsl\Term\Exists;
use AviationCode\Elasticsearch\Query\Dsl\Term\Fuzzy;
use AviationCode\Elasticsearch\Query\Dsl\Term\Ids;
use AviationCode\Elasticsearch\Query\Dsl\Term\Prefix;
use AviationCode\Elasticsearch\Query\Dsl\Term\Range;
use AviationCode\Elasticsearch\Query\Dsl\Term\Regexp;
use AviationCode\Elasticsearch\Query\Dsl\Term\Term;
use AviationCode\Elasticsearch\Query\Dsl\Term\TermsSet;
use AviationCode\Elasticsearch\Query\Dsl\Term\Wildcard;
use Illuminate\Contracts\Support\Arrayable;

class Filter implements Arrayable
{
    const KEY = 'filter';

    /**
     * Filter clauses to apply.
     *
     * @var array
     */
    private $clauses = [];

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
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_map(function ($clause) {
            return $clause->toArray();
        }, $this->clauses);
    }
}
