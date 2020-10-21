# Change Log

## NOT RELEASED

### Fixed

- AWS api-change: This Patch Manager release now supports Common Vulnerabilities and Exposure (CVE) Ids for missing packages via the DescribeInstancePatches API.
- AWS api-change: This Patch Manager release now supports searching for available packages from Amazon Linux and Amazon Linux 2 via the DescribeAvailablePatches API.

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
