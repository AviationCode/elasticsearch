<?php

namespace AviationCode\Elasticsearch\Events;

use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BulkDocumentsEvent
{
    /** @var Collection|DocumentEvent[] */
    public $events;

    /** @var bool */
    public $containsErrors = false;

    /**
     * BulkDocumentEvent constructor.
     *
     * @param Collection|Model[] $models
     * @param array $respone
     */
    public function __construct($models, $respone)
    {
        $this->events = new Collection();

        foreach ($respone['items'] as $i => $item) {
            foreach ($item as $action => $result) {
                if (isset($result['errors'])) {
                    $this->containsErrors = true;
                }

                $this->events->add(new DocumentEvent($models[$i], $action, $result));
            }
        }
    }
}
