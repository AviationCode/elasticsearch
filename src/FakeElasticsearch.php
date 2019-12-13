<?php

namespace AviationCode\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\Namespaces\IndicesNamespace;

class FakeElasticsearch extends Elasticsearch
{
    /**
     * @var IndicesNamespace|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    public $indicesClient;

    /**
     * FakeElasticsearch constructor.
     */
    public function __construct()
    {
        parent::__construct(null);

        $this->setElasticClient($this->client = \Mockery::spy(Client::class));

        $this->client
            ->shouldReceive('indices')
            ->andReturn($this->indicesClient = \Mockery::spy(IndicesNamespace::class));
    }
}
