<?php

namespace AviationCode\Elasticsearch\Console;

use AviationCode\Elasticsearch\Facades\Elasticsearch;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Console\Command;

/** @codeCoverageIgnore */
class CreateIndexCommand extends Command
{
    protected $signature = 'elastic:create-index';

    protected $description = 'Create index inside for the given model/idex.';

    public function handle(): int
    {
        $index = $this->ask('Provide full classpath to an eloquent model (Hit enter to skip):');

        if ($index) {
            $this->createIndexFromClass($index);
        }

        if (!$index) {
            $this->createIndex($this->ask('Which index would you like to create?'));
        }

        return 0;
    }

    private function createIndexFromClass(string $class): void
    {
        /** @var ElasticSearchable $model */
        $model = new $class();

        $this->info(class_basename($model) . ' found with elastic index "' . $model->getIndexName() . '"');

        $this->deleteExistingIndex($model->getIndexName());

        $this->info("Attempting to create '{$model->getIndexName()}' with mapping");
        $this->table(
            ['Field', 'Type', 'Options'],
            collect($model->getSearchMapping())->map(function ($mapping, $field) {
                $type = $mapping['type'] ?? '-';
                unset($mapping['type']);

                return [$field, $type, json_encode($mapping)];
            })
        );
        $model->elastic()->index()->create();

        $this->info('Index created');

        if ($this->confirm('Import existing data?')) {
            $this->getOutput()->progressStart($model->newQuery()->count());

            $qb = $model->newQuery();
            $chunkMethod = 'chunk';

            if ($model->getKeyType() === 'int') {
                $chunkMethod = 'chunkById';
            }

            $elastic = Elasticsearch::forModel($model);
            $qb->$chunkMethod(100, function ($models) use ($elastic) {
                $elastic->bulk($models);
                $this->getOutput()->progressAdvance($models->count());
            });

            $this->getOutput()->progressFinish();
        }
    }

    private function createIndex(string $index): void
    {
        $this->deleteExistingIndex($index);

        Elasticsearch::index()->create($index);

        if (!$this->confirm('Interactively define mapping?')) {
            return;
        }

        $mapping = [];
        while ($field = $this->ask('Field name?')) {
            $options = [];
            $options['type'] = $this->askWithCompletion(
                'Of which type?',
                ['text', 'keyword', 'object', 'date'],
                'text'
            );

            if ($options['type'] === 'date') {
                $options['ignore_malformed'] = true;
                $options['format'] = 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd';
            } elseif ($options['type'] === 'object') {
                $options['dynamic'] = $this->confirm('Dynamic properties?', true);
            }

            $options = array_merge($options, json_decode($this->ask('Any extra options (json)?', '[]'), true));

            $mapping[$field] = $options;
        }

        $this->info("Attempting put mapping '$index' with mapping");
        $this->table(['Field', 'Type', 'Options'], collect($mapping)->map(function ($mapping, $field) {
            $type = $mapping['type'];
            unset($mapping['type']);

            return [$field, $type, json_encode($mapping)];
        }));

        Elasticsearch::index()->putMapping($mapping, $index);
    }

    private function deleteExistingIndex(string $index): void
    {
        if (Elasticsearch::index()->exists($index)) {
            $this->warn("Index '$index' already exists");

            if ($this->confirm('Do you wish to delete existing index and all content?')) {
                Elasticsearch::index()->delete($index);

                $this->info('Index deleted');
            }
        }
    }
}
