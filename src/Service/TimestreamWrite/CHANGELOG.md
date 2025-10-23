# Change Log

## NOT RELEASED

### Changed

- Apply no CodingStandard from latest php-cs-fixer.

## 2.0.5

### Changed

- Sort exception alphabetically.

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

- The return type for `\AsyncAws\TimestreamWrite\ValueObject\Record::getVersion` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\TimestreamWrite\ValueObject\RejectedRecord::getExistingVersion` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

### Added

- AWS api-change: Added `us-gov-west-1-fips` region
- AWS enhancement: Documentation updates.

## 1.0.0

### Added

- AWS api-change: Added `ingest-fips-us-` and `ingest-us-` regions.

### Fixed

- Fixed crashes when details in error responses are missing.

## 0.1.1

### Added

- Added support for endpoint discovery

## 0.1.0

First version
