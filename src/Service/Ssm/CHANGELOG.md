# Change Log

## NOT RELEASED

### Added

- AWS api-change: AWS Systems Manager adds pagination support for DescribeDocumentPermission API
- AWS api-change: SSM Maintenance Window support for registering/updating maintenance window tasks without targets.
- AWS api-change: SSM Maintenance Window support for registering/updating maintenance window tasks without targets.

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
