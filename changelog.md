# Changelog

All notable changes to `Elasticsearch` will be documented in this file.

## Version 0.13.0

### Fixed

* Rename `AviationCode\Elasticsearch\Query\Dsl\FullText\Match`
  to `AviationCode\Elasticsearch\Query\Dsl\FullText\MatchQuery` to respect the new `match` preserved keyword as of PHP8.
  See [https://www.php.net/manual/en/reserved.keywords.php](https://www.php.net/manual/en/reserved.keywords.php)

## Version 0.12.0

### Added

* Fetch aggregations from SimplePaginator

## Version 0.11.0

### Added

* Add support for Count API

### Fixed

* Don't skip the first x documents equal to `$perPage`

## Version 0.10.0

### Added

* Add composite bucket support

## Version 0.9.0

### Added

* Add Laravel 8 support

### Removed

* Remove Laravel 5.X support

## Version 0.8.0

### Added

* `select($fields)` to query builder filtering results down subset of fields.

## Version 0.7.0

### Added

* Skip method on query builder (alias)
* From method to offset a query by X records. Is limited by the max-window size on your elastic configuration (default:
  10.000)
* Add paginate method on query builder compatible with Eloquent Builder paginator

### Fixed

* Add missing terms query

## Version 0.6.2

### Fixes

* Fix: Include path eloquent collection ([PR-57](https://github.com/AviationCode/elasticsearch/pull/57))

## Version 0.6.1

### Fixes

* Fix: publish vendor config ([PR-56](https://github.com/AviationCode/elasticsearch/pull/56))

## Version 0.6.0

### Breaking changes

Any value type aggregation (sum, value_count, avg, ...) which returned a `SimpleValue` object having `value()` method
and `value` property on its fluent class, no longer turn `SimpleValue`

```php
// Old 
$result->aggregation->total->value();

// New 
$result->aggregation->total;
``` 

Chaining aggregations will place them on the parent aggregation.

Expected aggregation structure:

* categories
    * sales
* total_sales

If you continue using the old structure it will produce the following aggregation structure

* categories
    * sales
        * total_sales

```php

// Old
$aggs->terms('categories', 'category')
    ->sum('categories.sales', 'item_count')
    ->sum('total_sales', 'item_count');

// New
$aggs->terms('categories', 'category')
    ->sum('sales', 'item_count');
$aggs->sum('total_sales', 'item_count');
```

### Changes

* Make SimpleValue no longer return an object instead return value
* Aggregation returns the aggregation object to add chaining

### Added

* Add Significant Terms bucket aggregation
* Add Perecentiles metric aggregation
* Add percentile ranks metric aggregation
* Add median absolute deviation metric aggregation
* Add string stats metric aggregation
* Add auto date histogram bucket aggregation
* Add filter bucket aggregation
* Add filters bucket aggregationn
* Add geohash grid bucket aggregation
* Add geotile grid bucket aggregation
* Add global bucket aggregation
* Add histogram bucket aggregation
* Add ip range bucket aggregation
* Add missing bucket aggregation
* Add nested bucket aggregation
* Add parent bucket aggregation
* Add range bucket aggregation
* Add rare terms bucket aggregation
* Add reverse nested bucket aggregation
* Add sampler bucket aggregation
* Add significant text bucket aggregation
* Add top hits metric aggregation
* Add adjacency matrix bucket aggregation
* Add children bucket aggregation
* Add diversified sampler bucket aggregation
* Add avg bucket pipeline aggregation
* Add max bucket aggregation
* Add min bucket aggregation
* Add sum bucket aggregation
* Add stats bucket aggregation
* Add extended stats bucket aggregation
* Add cumulative sum pipeline aggregation
* Add cumulative cardinality pipeline aggregation
* Add percentiles bucket pipeline aggregation
* Add bucket selector pipeline aggregation
* Add bucket sort pipeline aggregation
* Add serial diff pipeline aggregation

## Version 0.4.0

### Added

* Add `elastic:create-index` command to interactively create index
* Serialize aggregations to array / json.

### Fixes

* Fix search threw exception when requesting empty filter
* Fix zero size ignored in query
* Fix incorrect index name taken from eloquent models

## Version 0.3.0

### Added

* Use new extended config format (support https, basic auth...)

### Fixes

