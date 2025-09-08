# Change Log

## NOT RELEASED

### Changed

- Apply no CodingStandard from latest php-cs-fixer.

## 1.9.0

### Added

- AWS api-change: Rework regions configuration

## 1.8.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- AWS enhancement: Documentation updates.

## 1.7.0

### Added

- AWS api-change: Rework regions configuration.

## 1.6.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 1.5.6

### Changed

- AWS enhancement: Documentation updates.

## 1.5.5

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.5.4

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.5.3

### Changed

- AWS enhancement: Documentation updates.

## 1.5.2

### Changed

- AWS enhancement: Documentation updates.
- Allow passing explicit null values for optional fields of input objects

## 1.5.1

### Changed

- Improve parameter type and return type in phpdoc

## 1.5.0

### Added

- AWS enhancement: Documentation updates.

### Fixed

- Timestamp format

## 1.4.0

### Added

- AWS api-change: Added `fips-us-gov-east-1` and `fips-us-gov-west-1` regions
- AWS api-change: Adds new EventBridge Endpoint resources for disaster recovery, multi-region failover, and cross-region replication capabilities to help you build resilient event-driven applications.
- AWS enhancement: Documentation updates for EventBridge

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.

## 1.2.0

### Added

- AWS api-change: Adds TraceHeader to PutEventsRequestEntry to support AWS X-Ray trace-ids on events generated using the PutEvents operation.
- AWS enhancement: Documentation updates

### Fixed

- Assert the provided Input can be json-encoded.

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.0.1

### Fixed

- Fallback to default region config if provided region is not defined

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.1.0

First version
