<?php

namespace AviationCode\Elasticsearch\Console;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Model\ElasticSearchable;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use ReflectionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate elastic index to desired state';

    /**
     * @var Elasticsearch
     */
    private $elastic;

    /**
     * MigrateCommand constructor.
     * @param Elasticsearch $elastic
     */
    public function __construct(Elasticsearch $elastic)
    {
        parent::__construct();

        $this->addArgument('model', InputArgument::OPTIONAL, 'Model to migrate');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Force recreating index');
        $this->elastic = $elastic;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getOutput()->text('<info>Command started</info>');

        if ($class = $this->argument('model')) {
            $class = str_replace('/', '\\', $class);

            // The argument is a valid class
            if ($this->isElasticModel($class)) {
                $this->migrate($class);
            }

            $class = config('elasticsearch.model_namespace').$class;

            if ($this->isElasticModel($class)) {
                $this->migrate($class);
            }

            return;
        }

        // Find all classes
        $class = str_replace('\\', '/', config('elasticsearch.model_namespace'));
        $class = preg_replace("/[A-z0-9]*\//", '', $class, 1);

        collect(File::files(app_path().DIRECTORY_SEPARATOR.$class))
            ->map(function (\SplFileInfo $file) {
                $className = str_replace('.'.$file->getExtension(), '', $file->getFilename());

                return config('elasticsearch.model_namespace').$className;
            })
            ->filter(function ($class) {
                return $this->isElasticModel($class);
            })
            ->each(function ($class) {
                $this->migrate($class);
            });
    }

    private function migrate(string $class): void
    {
        $elastic = $this->elastic->forModel($class);
        $index = $elastic->model->getIndexName();
        $force = $this->option('force');

        if ($force && ! $this->confirm("Are you sure you want to delete {$index}")) {
            return;
        }

        if (! $elastic->index()->exists()) {
            $this->getOutput()->text("<comment>{$index} is missing creating...</comment>");

            $elastic->index()->create();
        } else {
            if ($force) {
                $this->getOutput()->text("<info>{$index}: Recreating index</info>");

                $elastic->index()->delete();
                $elastic->index()->create();
            } else {
                $this->getOutput()->text("<info>{$index}: already exists checking checking settings</info>");
            }
        }

        $info = $elastic->index()->info();
        $expectedMapping = $elastic->model->getSearchMapping();

        // Checking existing mappings
        foreach (Arr::get($info, 'mappings.properties', []) as $key => $mapping) {
            if (array_key_exists($key, $expectedMapping)) {
                if ($mapping === $expectedMapping[$key]) {
                    // Ok mapping valid
                    if ($this->getOutput()->isVerbose()) {
                        $this->getOutput()->text("<info>{$index}: $key mapping is valid</info>");
                    }
                } else {
                    // Mapping has changed
                    $this->getOutput()->warning(
                        "{$index}: $key has changed cannot automatically be updated\n".
                        'Expected: '.json_encode($expectedMapping[$key])."\n".
                        'Actual: '.json_encode($mapping)
                    );
                }
            } else {
                $this->getOutput()->text("<comment>{$index}: $key mapping wasn't expected</comment>");
            }
        }

        // Checking new mappings
        collect($expectedMapping)
            ->reject(function ($value, $key) use ($info) {
                return in_array($key, array_keys(Arr::get($info, 'mappings.properties', [])));
            })
            ->each(function ($value, $key) use ($elastic, $index) {
                $this->getOutput()->text("{$index}: Adding mapping for $key");

                $elastic->index()->putMapping([$key => $value]);
            });

        // Index data
        $this->getOutput()->text('<info>Indexing data '.$class.'</info>');
        $this->getOutput()->progressStart($elastic->model->newQuery()->count());
        $elastic->model->newQuery()->chunk(500, function ($models) {
            $this->elastic->add($models);
            $this->getOutput()->progressAdvance($models->count());
        });
        $this->getOutput()->progressFinish();
    }

    private function isElasticModel(string $class): bool
    {
        try {
            $reflection = new \ReflectionClass($class);
        } catch (ReflectionException $e) {
            return false;
        }

        return in_array(ElasticSearchable::class, $reflection->getTraitNames());
    }
}
