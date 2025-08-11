# Change Log

## NOT RELEASED

## 1.5.0

### Added

- AWS api-change: Added `cn-north-1` and `cn-northwest-1` regions

## 1.4.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

### Fixed

- Fix the lowest bound for the `symfony/polyfill-uuid` requirement

## 1.3.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

## 1.2.0

### Added

- AWS api-change: Added `us-iso-east-1`, `us-iso-west-1` and `us-isob-east-1` regions.

## 1.1.4

### Changed

- use strict comparison `null !==` instead of `!`

## 1.1.3

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.1.2

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.1.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.1.0

### Added

- AWS api-change: This release introduces automatic deletion of schedules in EventBridge Scheduler. If configured, EventBridge Scheduler automatically deletes a schedule after the schedule has completed its last invocation.
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Fixed

- Fix Timestamp format

## 0.1.0

First version
