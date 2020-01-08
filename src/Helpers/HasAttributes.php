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

        return null;
    }
}
