# Change Log

## NOT RELEASED

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.1.2

### Changed

- AWS enhancement: Documentation updates.

## 2.1.1

### Changed

- AWS enhancement: Documentation updates.

## 2.1.0

### Added

- AWS api-change: Added `fips-ca-west-1` regions

## 2.0.1

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for `\AsyncAws\Ssm\Result\PutParameterResult::getVersion` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Ssm\ValueObject\Parameter::getVersion` uses `int` instead of `string` to reflect the AWS type.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.0

### Added

- Added `us-iso-west-1` region
- AWS enhancement: Documentation updates.

## 1.3.0

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

### Removed

- The following regions are no longer supported by AWS and has been remove from
  the client: `ssm-facade-fips-us-east-1`, `ssm-facade-fips-us-east-2`, `ssm-facade-fips-us-gov-east-1`,
  `ssm-facade-fips-us-gov-west-1`, `ssm-facade-fips-us-west-1`, `ssm-facade-fips-us-west-2`.

### Added

- AWS api-change: Added region `fips-ca-central-1`

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Changed

- parameter `type` of `putParameter` is optional.

## 0.1.2

### Fixed

- Add return typehint for `getParametersByPath`

## 0.1.1

### Changed

- Support only version 1.0 of async-aws/core

## 0.1.0

First version
