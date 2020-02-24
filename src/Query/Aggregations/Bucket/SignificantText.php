<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class SignificantText extends Bucket
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
        'filter_duplicate_text',
        'source_fields',
    ];

    /**
     * SignificantText constructor.
     *
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('significant_text');

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
