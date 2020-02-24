<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class GeohashGrid extends Bucket
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
        'bounds',
        'precision',
        'size',
        'shard_size',
    ];

    /**
     * GeohashGrid constructor.
     * @param string $field
     * @param array $options
     */
    public function __construct(string $field, array $options = [])
    {
        parent::__construct('geohash_grid');

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
