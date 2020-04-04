# Change Log

## 0.4.0

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- Using `DateTimeImmutable` instead of `DateTimeInterface`

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

## 0.3.0

### Added

- Enums; `InvocationType`, `LogType`, `Runtime`

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

### Fixed

- `Action` and `Version` do not need to be part of every body.

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
