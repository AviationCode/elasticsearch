<?php

namespace AviationCode\Elasticsearch\Query\Aggregations\Bucket;

class Range extends Bucket
{
    /**
     * The field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $ranges = [];

    /**
     * Range constructor.
     *
     * @param string $field
     * @param array $ranges
     */
    public function __construct(string $field, $ranges = [])
    {
        parent::__construct('range', null);

        $this->field = $field;
        $this->ranges = $ranges;
    }


    /**
     * @param string $to
     * @param string|null $key
     *
     * @return $this
     */
    public function to($to, ?string $key = null): self
    {
        $this->ranges[] = ['to' => $to] + array_filter(['key' => $key]);

        return $this;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $key
     *
     * @return $this
     */
    public function range($from, $to, ?string $key = null): self
    {
        $this->ranges[] = ['from' => $from, 'to' => $to] + array_filter(['key' => $key]);

        return $this;
    }

    /**
     * @param float $from
     * @param string|null $key
     *
     * @return $this
     */
    public function from($from, ?string $key = null): self
    {
        $this->ranges[] = ['from' => $from] + array_filter(['key' => $key]);

        return $this;
    }
    
    /**
     * @inheritDoc
     */
    protected function toElastic(): array
    {
        return [
            'field' => $this->field,
            'keyed' => true,
            'ranges' => $this->ranges,
        ];
    }
}
