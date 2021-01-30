# Change Log

## NOT RELEASED

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.0.1

### Fixed

- Make sure required Map properties are validated before sending the request
- Make sure empty Map properties are converted to `{}` in Json request.
- If provided an unrecognized region, then fallback to default region config

## 1.0.0

### Added

- AWS api-change: This release adds a new ReplicaStatus REGION DISABLED for the Table description. This state indicates that the AWS Region for the replica is inaccessible because the AWS Region is disabled.
- AWS api-change: This release adds a new ReplicaStatus INACCESSIBLE_ENCRYPTION_CREDENTIALS for the Table description, indicating when a key used to encrypt a regional replica table is not accessible.

### Fixed

- Make sure we throw exception from async-aws/core

## 0.3.1

### Added

- Added operation `BatchWriteItem`
- Added operation `BatchGetItem`

### Fixed

- Internally we make a difference between null and an empty array

## 0.3.0

### Added

- Added operation `UpdateTimeToLiveInput`

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `listTables`, `query` and `scan`

## 0.2.0

### Added

- Support for async-aws/core 1.0

### Changed

- DTO classes are final

## 0.1.0

First version
