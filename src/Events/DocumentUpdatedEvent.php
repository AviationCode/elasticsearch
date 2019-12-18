<?php

namespace AviationCode\Elasticsearch\Events;

class DocumentUpdatedEvent extends DocumentEvent
{
    public function __construct($model, array $response)
    {
        parent::__construct($model, 'updated', $response);
    }
}
