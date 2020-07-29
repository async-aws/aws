# Change Log

## NOT RELEASED

## 1.0.0

No changes since 0.5.1.

## 0.5.1

### Added

- `SnsClient::createPlatformEndpoint()`
- `SnsClient::deleteEndpoint()`

## 0.5.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.4.2

### Fixed

- Add return typehint for `listSubscriptionsByTopic`

## 0.4.1

### Added

- Added operations `CreateTopic`, `DeleteTopic`, `ListSubscriptionsByTopic`, `Subscribe` and `Unsubscribe`.

### Changed

- Support only version 1.0 of async-aws/core

## 0.4.0

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- The `AsyncAws\Sns\Input\*` and `AsyncAws\Sns\ValueObject*` classes are marked final.

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

## 0.3.0

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
