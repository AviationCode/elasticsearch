<?php

namespace AviationCode\Elasticsearch\Tests\Unit\Query\Aggregations\Bucket;

use AviationCode\Elasticsearch\Query\Aggregations\Bucket\AdjacencyMatrix;
use AviationCode\Elasticsearch\Query\Dsl\Term\Terms;
use AviationCode\Elasticsearch\Tests\Unit\TestCase;

class AdjacencyMatrixTest extends TestCase
{
    /** @test **/
    public function it_builds_matrix_aggregation()
    {
        $this->assertEquals([
            'adjacency_matrix' => [
                'filters' => [
                    'grpA' => ['terms' => ['accounts' => ['hillary', 'sidney']]],
                    'grpB' => ['terms' => ['accounts' => ['donald', 'mitt']]],
                    'grpC' => ['terms' => ['accounts' => ['vladimir', 'nigel']]],
                ],
            ],
        ], (new AdjacencyMatrix([
            'grpA' => new Terms('accounts', ['hillary', 'sidney']),
            'grpB' => new Terms('accounts', ['donald', 'mitt']),
            'grpC' => new Terms('accounts', ['vladimir', 'nigel']),
        ]))->toArray());

        $this->assertEquals([
            'adjacency_matrix' => [
                'filters' => [
                    'grpA' => ['terms' => ['accounts' => ['hillary', 'sidney']]],
                    'grpB' => ['terms' => ['accounts' => ['donald', 'mitt']]],
                    'grpC' => ['terms' => ['accounts' => ['vladimir', 'nigel']]],
                ],
            ],
        ], (new AdjacencyMatrix([
            'grpA' => ['terms' => ['accounts' => ['hillary', 'sidney']]],
            'grpB' => ['terms' => ['accounts' => ['donald', 'mitt']]],
            'grpC' => ['terms' => ['accounts' => ['vladimir', 'nigel']]],
        ]))->toArray());

        $matrix = new AdjacencyMatrix();
        $matrix->add('grpA', ['terms' => ['accounts' => ['hillary', 'sidney']]]);
        $matrix->add('grpB', new Terms('accounts', ['donald', 'mitt']));
        $matrix->add('grpC', new Terms('accounts', ['vladimir', 'nigel']));

        $this->assertEquals([
            'adjacency_matrix' => [
                'filters' => [
                    'grpA' => ['terms' => ['accounts' => ['hillary', 'sidney']]],
                    'grpB' => ['terms' => ['accounts' => ['donald', 'mitt']]],
                    'grpC' => ['terms' => ['accounts' => ['vladimir', 'nigel']]],
                ],
            ],
        ], $matrix->toArray());
    }
}
