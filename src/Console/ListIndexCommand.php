<?php

namespace AviationCode\Elasticsearch\Console;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Model\Index;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/** @codeCoverageIgnore */
class ListIndexCommand extends Command
{
    protected $signature = 'elastic:indices';

    protected $description = 'List available indices';

    public function handle(Elasticsearch $elastic): void
    {
        $indices = $elastic->index()->list();

        $this->getOutput()->title('Internal indices');
        $this->formatTable($indices->filter->isInternal()->sortByDesc->docs_count);

        $this->getOutput()->title('Your indices');
        $this->formatTable($indices->reject->isInternal()->sortByDesc->docs_count);
    }

    private function formatTable(Collection $data): void
    {
        $headers = ['index', 'status', 'health', 'document count', 'size'];

        $this->table($headers, $data->map(function (Index $value) {
            return [
                $value->index,
                $value->status,
                $value->health,
                number_format($value->docs_count),
                $value->store_size,
            ];
        }));
    }
}
