# Change Log

## NOT RELEASED

### Added

- AWS enhancement: Documentation updates.

## 1.5.0

### Added

- AWS api-change: Added filter on `logGroupIdentifier` in  `createLogGroup`, `DescribeLogStreams` and `FilterLogEvents`

## 1.4.0

### Added

- AWS api-change: Added `fips-us-gov-east-1` and `fips-us-gov-west-1` regions
- AWS enhancement: Documentation updates.

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- Added operation `filterLogEvents`

## 1.2.0

### Fixed

- Assert the provided Input can be json-encoded.

### Added

- AWS api-change: Update documentation
- Added operation `createLogGroup`

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.0.2

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.0.1

### Fixed

- Make sure we throw exception from async-aws/core

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `describeLogStreams`

## 0.1.0

First version
