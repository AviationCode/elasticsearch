<?php

namespace AviationCode\Elasticsearch\Model;

use Illuminate\Support\Fluent;

/**
 * Class Index
 *
 * @property-read string $index
 * @property-read string $status
 * @property-read string $health
 * @property-read integer $docs_count
 * @property-read integer $docs_deleted
 * @property-read string $store_size
 * @property-read string $pri_store_size
 * @property-read string $uuid
 * @property-read integer $pri
 * @property-read integer $rep
 */
class Index extends Fluent
{
    /**
     * Index constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (is_string($key) && strpos($key, '.')) {
                unset($attributes[$key]);

                $attributes[str_replace('.', '_', $key)] = $value;
            }
        }

        parent::__construct($attributes);
    }

    /**
     * Is the index one owned by one of the elastic stack components?
     *
     * @return bool
     */
    public function isInternal(): bool
    {
        return substr($this->index, 0, 1) === '.';
    }
}
