<?php

namespace AviationCode\Elasticsearch\Events;

class DocumentCreatedEvent extends DocumentEvent
{
    public function __construct($model, array $response)
    {
        parent::__construct($model, 'created', $response);
    }
}
