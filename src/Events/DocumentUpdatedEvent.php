<?php

namespace AviationCode\Elasticsearch\Events;

class DocumentUpdatedEvent extends DocumentEvent
{
    /**
     * DocumentUpdatedEvent constructor.
     *
     * @param mixed $model
     * @param array $response
     */
    public function __construct($model, array $response)
    {
        parent::__construct($model, 'updated', $response);
    }
}
