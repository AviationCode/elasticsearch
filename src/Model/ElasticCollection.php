<?php

namespace AviationCode\Elasticsearch\Model;

use AviationCode\Elasticsearch\Model\Aggregations\Aggregation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class ElasticCollection extends Collection
{
    public $took = -1;

    public $timed_out = false;

    public $shards = [];

    public $max_score = null;

    public $total;

    public $total_relation;

    public $aggregations;

    /**
     * Set search meta from response.
     *
     * @param array $response
     * @return ElasticCollection
     */
    public function mapMeta(array $response): self
    {
        $this->took = $response['took'];
        $this->timed_out = $response['timed_out'];
        $this->shards = $response['_shards'];
        $this->max_score = Arr::get($response, 'hits.max_score');
        $this->total = Arr::get($response, 'hits.total.value');
        $this->total_relation = Arr::get($response, 'hits.total.relation');

        return $this;
    }

    /**
     * This is true when the query exceeds the max results elasticsearch
     * is able to return. By default elastic cannot return more than
     * 10000 records with offset flag.
     *
     * @return bool
     */
    public function totalExceedsLimit(): bool
    {
        return $this->total_relation === 'gte';
    }

    /**
     * Map aggregation data onto collection instance.
     *
     * @param array $response
     * @return $this
     */
    public function mapAggregations(array $response): self
    {
        $this->aggregations = new Aggregation($response['aggregations'] ?? []);

        return $this;
    }

    /**
     * Map models onto collection instance.
     *
     * @param array $response
     * @param Model $model
     * @return $this
     */
    public function mapModels(array $response, ?Model $model = null): self
    {
        if (! $model) {
            return $this;
        }

        $this->items = array_map(function ($item) use ($model) {
            /** @var ElasticSearchable $model */
            return $model->newFromElasticBuilder($item);
        }, $response['hits']['hits']);

        return $this;
    }

    /**
     * Parse an elasticsearch response onto eloquent collection class.
     *
     * @param array $response
     * @param Model|null $model
     * @return $this
     */
    public static function parse(array $response, ?Model $model = null): self
    {
        $collection = new static;

        // Map the meta information such as time taken, total number or results and success
        // Meta block contains useful information about the performance of the elastic
        // query performed. If the query times out timed_out flag will be true with
        // Empty hits array returned. It's important to check the meta info.
        $collection->mapMeta($response);

        // We get both meta information on the object and the object source back
        // The source can be used to reconstruct an eloquent model while
        // meta information can give indication of the relevance.
        $collection->mapModels($response, $model);

        // When aggregation query is performed these values will not map onto eloquent object
        // for this reason we store then onto the collection object in collection instance.
        $collection->mapAggregations($response);

        return $collection;
    }
}
