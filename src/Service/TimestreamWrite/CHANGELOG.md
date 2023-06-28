# Change Log

## NOT RELEASED

### BC-BREAK

- The return type for `\AsyncAws\TimestreamWrite\ValueObject\Record::getVersion` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\TimestreamWrite\ValueObject\RejectedRecord::getExistingVersion` uses `int` instead of `string` to reflect the AWS type.

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
