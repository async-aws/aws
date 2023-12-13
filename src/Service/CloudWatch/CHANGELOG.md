# Change Log

## NOT RELEASED

## 1.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 0.2.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Added FORBIDDEN status code
- AWS api-change: Added includeLinkedAccounts and owningAccount in ListMetrics
- BC BREAK: ListMetricsOutput now yields over `metrics` AND `owningAccounts`

## 0.1.1

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions

## 0.1.0

First version
