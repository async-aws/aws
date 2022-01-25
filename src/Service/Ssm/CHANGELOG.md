# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added `ca-central-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- Added operation `DeleteParameters`

## 1.2.1

### Fixed

- Assert the provided Input can be json-encoded.
- AWS enhancement: Documentation updates for AWS Systems Manager.

## 1.2.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- AWS enhancement: Added region "us-iso-east-1".
- Added domain exceptions

## 1.1.1

### Fixed

- Make sure we throw exception from async-aws/core
- If provided an unrecognized region, then fallback to default region config
- AWS api-change: Improve documentation for `GetParametersByPathRequest::$Path`.

## 1.1.0

### Added

- AWS api-change: Added region `fips-ca-central-1`

### Removed

The following regions are no longer supported by AWS and has been remove from
the client: `ssm-facade-fips-us-east-1`, `ssm-facade-fips-us-east-2`, `ssm-facade-fips-us-gov-east-1`,
`ssm-facade-fips-us-gov-west-1`, `ssm-facade-fips-us-west-1`, `ssm-facade-fips-us-west-2`.

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Changed

- parameter `type` of `putParameter` is optional.

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.1.2

### Fixed

- Add return typehint for `getParametersByPath`

## 0.1.1

### Changed

- Support only version 1.0 of async-aws/core

## 0.1.0

First version
