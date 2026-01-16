# Change Log

## NOT RELEASED

## 2.2.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 2.1.2

### Changed

- Sort exception alphabetically.

### Fixed

- Fix the lowest bound for the `symfony/polyfill-uuid` requirement

## 2.1.1

### Changed

- Avoid usage of `alias` when use statement refers to self

## 2.1.0

### Added

- AWS api-change: This release adds support for Query Insights, a feature that provides details of query execution, enabling users to identify areas for improvement to optimize their queries, resulting in improved query performance and lower query costs.

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 2.0.4

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.0.3

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.0.2

### Changed

- AWS enhancement: Documentation updates.

## 2.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for the methods `getCumulativeBytesMetered` and `getCumulativeBytesScanned` of `\AsyncAws\TimestreamQuery\ValueObject\QueryStatus` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- Nothing changed. Let's release a First stable version

## 0.1.1

### Added

- Added support for endpoint discovery

## 0.1.0

First version
