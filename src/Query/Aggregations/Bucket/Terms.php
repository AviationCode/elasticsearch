<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Model\Aggregations\Bucket\Terms as TermsModel;

class Terms extends Bucket
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
        'size',
        'missing',
        'include',
        'exclude',
        'order',
        'min_doc_count',
        'show_term_doc_count_error',
    ];

    /**
     * Terms constructor.
     *
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('terms', TermsModel::class);

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
