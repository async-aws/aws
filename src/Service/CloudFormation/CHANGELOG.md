# Change Log

## NOT RELEASED

## 1.0.0

### Added support for PHP 8

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
