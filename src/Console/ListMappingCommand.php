<?php

namespace AviationCode\Elasticsearch\Console;

use AviationCode\Elasticsearch\Elasticsearch;
use AviationCode\Elasticsearch\Exceptions\IndexNotFoundException;
use Illuminate\Console\Command;

/** @codeCoverageIgnore */
class ListMappingCommand extends Command
{
    protected $signature = 'elastic:list-mapping {index? : The index}';

    protected $description = 'Delete an index.';

    public function handle(Elasticsearch $elastic): void
    {
        $index = $this->argument('index');

        if (! $index) {
            $indices = $elastic->index()->list()->pluck('index');

            $index = $this->askWithCompletion('Which index?', $indices->toArray());
        }

        try {
            $result = $elastic->index()->info($index);

            $this->printTable($result['mappings']['properties']);
        } catch (IndexNotFoundException $exception) {
            $this->getOutput()->error("$index does not exist!");
        }
    }

    private function printTable($properties): void
    {
        $data = $this->formatProperties($properties);

        $this->table(['field', 'type', 'properties'], $data);
    }

    private function formatProperties($properties, &$data = [], $level = ''): array
    {
        foreach ($properties as $field => $info) {
            $key = $level . $field;

            $data[$key] = ['field' => $key];

            if (isset($info['fields'])) {
                $this->formatProperties($info['fields'], $data, "$field.");

                unset($info['fields']);
            }

            if (isset($info['properties'])) {
                $this->formatProperties($info['properties'], $data, "$field.");

                unset($info['properties']);
            }

            if (!isset($info['type'])) {
                unset($data[$key]);

                continue;
            }

            $data[$key]['type'] = $info['type'];
            unset($info['type']);

            $data[$key]['properties'] = json_encode($info);
        }

        return $data;
    }
}
