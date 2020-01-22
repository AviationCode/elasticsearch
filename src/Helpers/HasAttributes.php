<?php

namespace AviationCode\Elasticsearch\Helpers;

trait HasAttributes
{
    protected $attributes = [];

    public function __set($property, $value)
    {
        $this->attributes[$property] = $value;
    }

    public function __get($property)
    {
        if (isset($this->attributes[$property])) {
            return $this->attributes[$property];
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof \JsonSerializable) {
                return $value->jsonSerialize();
            }
            return $value;
        }, $this->attributes);
        return $this->attributes;
    }
}
