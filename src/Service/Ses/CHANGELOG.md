# Change Log

## NOT RELEASED

## 1.4.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions

## 1.3.1

### Fixed

- Fallback to default region config if provided region is not defined

## 1.3.0

### Added

- Enables customers to manage their own contact lists and end-user subscription preferences

## 1.2.0

### Added

- Support for PHP 8.
- Support for `FromEmailAddressIdentityArn` and `FeedbackForwardingEmailAddressIdentityArn` in `SendEmailRequest`.

## 1.1.1

### Fixed

- Fix invalid signature in SES client because of wrong Scoped Service.

## 1.1.0

### Deprecation

- Protected methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` of `SesClient` are deprecated and will be removed in 2.0

## 1.0.0

### Added

- Support for async-aws/core 1.0.

## 0.4.0

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- The `AsyncAws\Ses\Input\*` and `AsyncAws\Ses\ValueObject*` classes are marked final.

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

## 0.3.0

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
