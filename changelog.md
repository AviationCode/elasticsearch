# Changelog

All notable changes to `Elasticsearch` will be documented in this file.

## Version 0.6.0
### Breaking changes

Any value type aggregation (sum, value_count, avg, ...) which returned a `SimpleValue` object having `value()` method
and `value` property on it's fluent class, no longer turn `SimpleValue`

```php
// Old 
$result->aggregation->total->value();

// New 
$result->aggregation->total;
``` 

## Version 0.5.2
## Version 0.5.1
## Version 0.5.0
### Added

### Fixes


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

