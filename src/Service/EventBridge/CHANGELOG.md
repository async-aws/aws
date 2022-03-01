# Change Log

## NOT RELEASED

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.

## 1.2.0

### Fixed

- Assert the provided Input can be json-encoded.

### Added

- AWS api-change: Adds TraceHeader to PutEventsRequestEntry to support AWS X-Ray trace-ids on events generated using the PutEvents operation.
- AWS enhancement: Documentation updates

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
