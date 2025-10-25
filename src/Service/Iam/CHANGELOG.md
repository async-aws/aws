# Change Log

## NOT RELEASED

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 1.7.0

### Added

- AWS api-change: Updated IAM ServiceSpecificCredential support to include expiration, API Key output format instead of username and password for services that will support API keys, and the ability to list credentials for all users in the account for a given service configuration.

## 1.6.1

### Changed

- Normalize the composer requirements
- Sort exception alphabetically.

## 1.6.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 1.5.4

### Changed

- use strict comparison `null !==` instead of `!`

## 1.5.3

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.5.2

### Changed

- AWS enhancement: Documentation updates.

## 1.5.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.5.0

### Added

- AWS enhancement: Documentation updates.
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.0

### Added

- Added `IamClient::listServiceSpecificCredentials()`
- Added `IamClient::deleteServiceSpecificCredential()`
- Added `IamClient::putUserPolicy()`
- Added `IamClient::deleteUserPolicy()`
- Added `IamClient::createServiceSpecificCredential()`

## 1.3.0

- Empty release

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
