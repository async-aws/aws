# Change Log

## NOT RELEASED

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 1.6.1

### Changed

- AWS enhancement: Documentation updates.

## 1.6.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

## 1.5.0

### Added

- AWS api-change: TODO

## 1.4.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

## 1.3.0

### Added

- AWS api-change: Added `KmsAccessDeniedException`, `KmsInvalidStateException` and `KmsThrottlingException` exceptions

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.2.3

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.2.2

### Changed

- AWS enhancement: Documentation updates.

## 1.2.1

### Changed

- AWS enhancement: Documentation updates.
- Allow passing explicit null values for optional fields of input objects

## 1.2.0

### Added

- AWS api-change: Adding `INVALID_ROUTING_CONFIGURATION` reason to ValidationExceptionReason enum
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

### Added

- AWS api-change: Add a `ValidationException` exception

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- Added operation `stopExecution`

## 0.1.2

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions

## 0.1.1

### Added

- Added operation `sendTaskFailure`
- Added operation `sendTaskHeartbeat`
- Added operation `sendTaskSuccess`

## 0.1.0

First version
