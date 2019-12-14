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

        $this->setClient($this->client = \Mockery::mock(Client::class));

        $this->client
            ->shouldReceive('indices')
            ->andReturn($this->indicesClient = \Mockery::mock(IndicesNamespace::class));
    }
}
