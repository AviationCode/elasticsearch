<?php

namespace AviationCode\Elasticsearch\Console;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

/** @codeCoverageIgnore */
class DeleteIndexCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'elastic:delete-index 
                    {index? : The index to be deleted}
                    {--force : Force the operation to run when in production}';

    protected $description = 'Delete an index.';

    public function handle(Elasticsearch $elastic): void
    {
        $index = $this->argument('index');

        if (!$index) {
            $indices = $elastic->index()->list()->pluck('index');

            $index = $this->askWithCompletion('Which index do you want to delete?', $indices->toArray());
        }

        if (!$this->confirmToProceed($index.' - Ensure you have a backup!')) {
            return;
        }

        try {
            $elastic->index()->delete($index);

            $this->getOutput()->success("$index deleted!");
        } catch (IndexNotFoundException $exception) {
            $this->getOutput()->error("$index does not exist!");
        }
    }
}
