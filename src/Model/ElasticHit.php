<?php

namespace AviationCode\Elasticsearch\Model;

final class ElasticHit extends ElasticsearchModel
{
    /**
     * Create a generic model bound to specific index.
     *
     * @param string $index
     * @return static
     */
    public static function onIndex(?string $index = null): self
    {
        $model = new static();

        $model->indexName = $index;

        return $model;
    }
}
