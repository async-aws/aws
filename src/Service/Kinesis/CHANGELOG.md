# Change Log

## NOT RELEASED

## 3.3.0

### Added

- AWS api-change: added `eu-isoe-west-1` region
- AWS api-change: Amazon KDS now supports tagging and attribute-based access control (ABAC) for enhanced fan-out consumers.

### Changed

- Sort exception alphabetically.

## 3.2.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 3.1.1

### Changed

- use strict comparison `null !==` instead of `!`

## 3.1.0

### Added

- AWS api-change: This release includes support to add tags when creating a stream

### Changed

- Enable compiler optimization for the `sprintf` function.

## 3.0.3

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 3.0.2

### Changed

- AWS enhancement: Documentation updates.

## 3.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 3.0.0

### BC-BREAK

- The return type for `\AsyncAws\Kinesis\Result\GetRecordsOutput::getMillisBehindLatest` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 2.0.0

### BC-BREAK

- BC BREAK: `listStreams` now yield over stream names AND descriptions

### Added

- AWS api-change: Added StreamARN parameter for Kinesis Data Streams APIs. Added a new opaque pagination token for ListStreams. SDKs will auto-generate Account Endpoint when accessing Kinesis Data Streams.
- AWS api-change: added `fips-us-gov-east-1` and `fips-us-gov-west-1` regions

### Fixed

- Timestamp format

## 1.1.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS api-change: Amazon Kinesis Data Streams now supports on demand streams.

## 1.0.0

### Fixed

- Handle not-defined array properties

## 0.1.0

First version
