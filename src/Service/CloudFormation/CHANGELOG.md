# Change Log

## NOT RELEASED

## 1.3.0

### Added

- AWS api-change: Add support to AWS CloudFormation Hooks.

## 1.2.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- Added `describeStackDriftDetectionStatus` method.

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- AWS api-change: AWS CloudFormation allows you to iteratively develop your applications when failures are encountered without rolling back successfully provisioned resources. By specifying stack failure options, you can troubleshoot resources in a CREATE_FAILED or UPDATE_FAILED status.

## 1.0.2

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.0.1

### Fixed

- Make sure we throw exception from async-aws/core

## 1.0.0

### Added

- Support for PHP 8

## 0.5.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `describeStackEvents` and `describeStacks`

## 0.4.1

### Changed

- Support only version 1.0 of async-aws/core

## 0.4.0

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- Using `DateTimeImmutable` instead of `DateTimeInterface`
- The `AsyncAws\CloudFormation\Enum\*`, `AsyncAws\CloudFormation\Input\*` and `AsyncAws\CloudFormation\ValueObject*` classes are marked final.

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

## 0.3.0

### Added

- Enums; `Capability`, `ResourceStatus`, `StackDriftStatus`, `StackStatus`

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
