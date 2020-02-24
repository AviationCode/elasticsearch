<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class RareTerms extends Bucket
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    protected $allowedOptions = [
        'max_doc_count',
        'precision',
        'include',
        'exclude',
        'missing',
    ];

    /**
     * RareTerms constructor.
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('rare_terms');

        $this->field = $field;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return array_merge(['field' => $this->field], $this->allowedOptions($this->options));
    }
}
