<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Term;

use Illuminate\Contracts\Support\Arrayable;

class Term implements Arrayable
{
    const KEY = 'term';

    /**
     * Field you wish to search.
     *
     * @var string
     */
    private $field;

    /**
     * Term you wish to find in the provided <field>. To return a document,
     * the term must exactly match the field value, including whitespace and capitalization.
     *
     * @var mixed
     */
    private $value;

    /**
     * Boost to be applied. Use null to let elastic use the default value.
     *
     * @var null|float
     */
    private $boost;

    /**
     * Term constructor.
     *
     * @param string $field
     * @param mixed $value
     * @param float|null $boost
     */
    public function __construct(string $field, $value, ?float $boost = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->boost = $boost;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $params = ['value' => $this->value];

        if ($this->boost) {
            $params['boost'] = $this->boost;
        }

        return [
            self::KEY => [$this->field => $params],
        ];
    }
}
