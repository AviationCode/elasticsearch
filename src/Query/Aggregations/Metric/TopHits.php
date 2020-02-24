<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Metric;

use AviationCode\Elasticsearch\Query\Aggregations\Aggregation;
use AviationCode\Elasticsearch\Query\Aggregations\HasAggregations;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class TopHits implements Arrayable
{
    use HasAggregations;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $allowedOptions = [
        'sort',
        '_source',
        'size',
        'explain',
        'highlight',
        'stored_fields',
        'script_fields',
        'docvalue_fields',
        'seq_no_primary_term',
        'version',
    ];

    /**
     * Bucket constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->key = 'top_hits';
        $this->aggregations = new Aggregation();

        $this->options = $options;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function size(int $size): self
    {
        $this->options['size'] = $size;

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $field, $direction = 'asc'): self
    {
        $this->options['sort'][$field] = ['order' => $direction];

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function fields(array $fields): self
    {
        $this->options['_source']['includes'] = $fields;

        return $this;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function addField(string $field): self
    {
        $this->options['_source']['includes'][] = $field;

        return $this;
    }

    /**
     * @param string $field
     * @param string $scripted
     * @return $this
     */
    public function scriptedField(string $field, string $scripted): self
    {
        $this->options['script_fields'][$field] = ['script' => ['lang' => 'painless', 'source' => $scripted]];

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function includeSequenceNumbers(bool $value = true): self
    {
        $this->options['seq_no_primary_term'] = $value;

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function version(bool $value = true): self
    {
        $this->options['version'] = $value;

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function explain(bool $value = true): self
    {
        $this->options['explain'] = $value;

        return $this;
    }

    /**
     * Return array structure of the object as an elastic query.
     */
    protected function toElastic()
    {
        if (count($options = $this->allowedOptions($this->options))) {
            return $options;
        }

        return new \stdClass();
    }

    /**
     * Only return the options which are allowed.
     *
     * @param array $options
     * @return array
     */
    protected function allowedOptions(array $options): array
    {
        return Arr::only($options, $this->allowedOptions);
    }
}
