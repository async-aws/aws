# Change Log

## NOT RELEASED

### Fixed

- Assert the provided Input can be json-encoded.

### Added

- AWS api-change: Update documentation

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
