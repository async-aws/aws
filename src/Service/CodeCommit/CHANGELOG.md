# Change Log

## NOT RELEASED

### Added

- AWS api-change: CreateRepository API now throws OperationNotAllowedException when the account has been restricted from creating a repository.

## 1.1.2

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.1.1

### Changed

- AWS enhancement: Documentation updates.

## 1.1.0

### Added

- AWS api-change: AWS CodeCommit now supports customer managed keys from AWS Key Management Service. UpdateRepositoryEncryptionKey is added for updating the key configuration. CreateRepository, GetRepository, BatchGetRepositories are updated with new input or output parameters.

## 1.0.2

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 1.0.1

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- Added operation `ListRepositories`
- Added operation `CreateRepository`
- Added operation `DeleteRepository`

## 0.1.1

### Added

- Added operation `GetCommit`
- Added operation `PutRepositoryTriggers`

## 0.1.0

First version
