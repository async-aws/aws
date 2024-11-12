# Change Log

## NOT RELEASED

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
