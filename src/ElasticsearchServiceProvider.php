<?php

namespace AviationCode\Elasticsearch;

use AviationCode\Elasticsearch\Console\CreateIndexCommand;
use AviationCode\Elasticsearch\Console\DeleteIndexCommand;
use AviationCode\Elasticsearch\Console\ListIndexCommand;
use AviationCode\Elasticsearch\Console\ListMappingCommand;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticsearchServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/elasticquent.php' => config_path('elasticquent.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateIndexCommand::class,
                ListIndexCommand::class,
                ListMappingCommand::class,
                DeleteIndexCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/elasticsearch.php', 'elasticsearch');

        $this->app->singleton('elasticsearch', function ($app) {
            return new Elasticsearch();
        });

        // Register the service the package provides.
        $this->app->singleton('elasticsearch.client', function ($app) {
            return ClientBuilder::fromConfig(config('elasticsearch.config'));
        });
    }
}
