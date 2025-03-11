# Change Log

## NOT RELEASED

### Added

- AWS api-change: Make supported regions explicit

## 1.8.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

### Changed

- AWS enhancement: Documentation updates.

## 1.7.5

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 1.7.4

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.7.3

### Changed

- AWS enhancement: Documentation updates.

## 1.7.2

### Changed

- AWS enhancement: Documentation updates.

## 1.7.1

### Changed

- AWS enhancement: Documentation updates.

## 1.7.0

### Added

- AWS api-change: Added `fips-ca-west-1` regions

## 1.6.0

### Added

- AWS api-change: Message Archiving and Replay is now supported in Amazon SNS for FIFO topics.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.5.0

### Added

- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.1

### Fixed

- Fixed crashes when details in error responses are missing.

## 1.4.0

### Added

- AWS api-change: Added `fips-us-gov-east-1` and `fips-us-gov-west-1` regions
- AWS api-change: Amazon SNS introduces the Data Protection Policy APIs, which enable customers to attach a data protection policy to an SNS topic. This allows topic owners to enable the new message data protection feature to audit and block sensitive data that is exchanged through their topics.

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates for Amazon SNS.
- Added operation `PublishBatch`

## 1.2.1

### Fixed

- AWS enhancement: Documentation updates for Amazon SNS.

## 1.2.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions

## 1.1.1

### Fixed

- AWS enhancement: Documentation updates for Amazon SNS.
- Make sure required Map properties are validated before sending the request
- Make sure we throw exception from async-aws/core
- If provided an unrecognized region, then fallback to default region config

## 1.1.0

### Added

- AWS api-change: SNS now supports a new class of topics: FIFO (First-In-First-Out). FIFO topics provide strictly-ordered, deduplicated, filterable, encryptable, many-to-many messaging at scale.

## 1.0.0

### Added

- Support for PHP 8

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

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- The `AsyncAws\Sns\Input\*` and `AsyncAws\Sns\ValueObject*` classes are marked final.

## 0.3.0

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
