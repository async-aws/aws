# Change Log

## NOT RELEASED

## 1.2.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Added `us-fips` regions
- AWS api-change: Use specific configuration for `us` regions

## 1.1.1

### Fixed

- AWS enhancement: Documentation updates for AWS Identity and Access Management (IAM).

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.

## 1.0.1

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.0.0

### Fixed

- Make sure we throw exception from async-aws/core

## 0.2.2

### Added

- `IamClient::addUserToGroup()`
- `IamClient::createAccessKey()`
- `IamClient::deleteAccessKey()`

## 0.2.1

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.1.0

First version
