<?php

namespace AviationCode\Elasticsearch\Query\Dsl\Geo;

use Illuminate\Contracts\Support\Arrayable;

class GeoShape implements Arrayable
{
    private const KEY = 'geo_shape';

    public const INTERSECTS = 'intersects';
    public const DISJOINT = 'disjoint';
    public const WITHIN = 'within';
    public const CONTAINS = 'contains';

    public const INDEXED_SHAPE = 'indexed_shape';

    /**
     * @var string
     */
    private $field;

    /**
     * @var array
     */
    private $shape;

    /**
     * @var string
     */
    private $relation;

    /**
     * @var bool
     */
    private $unmapped;

    /**
     * GeoShape constructor.
     *
     * @param string $field
     * @param array $shape
     * @param string|null $relation
     * @param bool $unmapped
     */
    public function __construct(string $field, array $shape, ?string $relation = null, bool $unmapped = false)
    {
        $this->field = $field;
        $this->shape = $shape;
        $this->relation = $relation;
        $this->unmapped = $unmapped;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $params = [];

        if ($this->relation === static::INDEXED_SHAPE) {
            $params['indexed_shape'] = $this->shape;
        } else {
            $params['shape'] = $this->shape;
        }

        if ($this->unmapped) {
            $params['ignored_unmapped'] = true;
        }

        if ($this->relation && $this->relation !== static::INDEXED_SHAPE) {
            $params['relation'] = $this->relation;
        }

        return [static::KEY => [$this->field => $params]];
    }
}
