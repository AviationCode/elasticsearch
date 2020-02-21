<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class SignificantTerms extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    protected $allowedOptions = [
        'jlh',
        'include_negatives',
        'background_is_superset',
        'background_filter',
        'chi_square',
        'execution_hint',
        'gnd',
        'percentage',
        'shard_size',
        'shard_min_doc_count',
        'min_doc_count',
        'script',
    ];

    /**
     * Terms constructor.
     *
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('significant_terms');

        $this->field = $field;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function toElastic(): array
    {
        return array_merge(['field' => $this->field], $this->allowedOptions($this->options));
    }
}
