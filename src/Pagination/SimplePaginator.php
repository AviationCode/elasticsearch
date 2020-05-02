<?php

namespace AviationCode\Elasticsearch\Pagination;

use AviationCode\Elasticsearch\Model\ElasticCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class SimplePaginator
 *
 * @property ElasticCollection $items
 */
class SimplePaginator extends LengthAwarePaginator
{
    /**
     * SimplePaginator constructor.
     *
     * @param ElasticCollection $items
     * @param int $perPage
     * @param int $currentPage
     * @param string $pageName
     */
    public function __construct(ElasticCollection $items, int $perPage, int $currentPage, string $pageName = 'page')
    {
        parent::__construct($items, $items->total, $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);

        // Avoid going over max-window limit
        if ($this->items->total_relation == 'gte') {
            $this->lastPage--;
        }
    }
}
