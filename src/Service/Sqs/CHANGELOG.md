# Change Log

## NOT RELEASED

## 2.7.1

### Changed

- AWS enhancement: Documentation updates.

## 2.7.0

### Added

- AWS api-change: Rework regions configuration

### Changed

- AWS enhancement: Documentation updates.

## 2.6.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

## 2.5.0

### Added

- AWS api-change: rewrite declaration of regions
- AWS api-change: Rework regions configuration.

## 2.4.0

### Added

- AWS api-change: Added `fips-ca-central-1` and `fips-ca-west-1` regions
- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

## 2.3.0

### Added

- AWS enhancement: In-flight message typo fix from 20k to 120k.

## 2.2.1

### Changed

- use strict comparison `null !==` instead of `!`

## 2.2.0

### Added

- Add AddPermission endpoint

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.1.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers
- AWS enhancement: Documentation updates.

## 2.1.0

### Added

- AWS api-change: This release adds MessageSystemAttributeNames to ReceiveMessageRequest to replace AttributeNames.

### Changed

- AWS enhancement: Documentation updates.

## 2.0.0

### BC-BREAK

- AWS api-change: This release enables customers to call SQS using AWS JSON-1.0 protocol.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 1.9.0

### Added

- AWS enhancement: Documentation updates.

### Changed

- Improve parameter type and return type in phpdoc

## 1.8.0

### Added

- AWS api-change: Added `DEAD_LETTER_QUEUE_SOURCE_ARN` attribute name

## 1.7.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS api-change: Amazon SQS adds a new queue attribute, `SqsManagedSseEnabled`, which enables server-side queue encryption using SQS owned encryption keys.
- Added operations `ChangeMessageVisibilityBatch`, `DeleteMessageBatch`, `SendMessageBatch`

## 1.6.0

### Added

- AWS enhancement: Documentation updates for Amazon SQS
- AWS api-change: Amazon SQS adds a new queue attribute, RedriveAllowPolicy, which includes the dead-letter queue redrive permission parameters. It defines which source queues can specify dead-letter queues as a JSON object.

## 1.5.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions

## 1.4.0

### Added

- AWS api-change: Amazon SQS adds queue attributes to enable high throughput FIFO.

### Fixed

- Make sure required Map properties are validated before sending the request
- If provided an unrecognized region, then fallback to default region config

## 1.3.2

### Fixed

- Make sure we throw exception from async-aws/core

## 1.3.1

### Fixed

- Updated the values allowed in `ReceiveMessageRequest::$AttributeNames`. The SQS specification did not match the documentation and the expected values from the server.

## 1.3.0

### Added

- Support for PHP 8
- Pagination support for `ListQueue`
- `ListQueueRequest::$NextToken`
- `ListQueueRequest::$MaxResults`

## 1.2.0

### Deprecated

- Protected methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` of `SqsClient` are deprecated and will be removed in 2.0

## 1.1.0

### Added

- Added enum `MessageSystemAttributeName`, `MessageSystemAttributeNameForSends` and `QueueAttributeName`.

## 1.0.1

### Fixed

- Use camelCase for all getter methods.
- Add return typehint for `listQueues`

## 1.0.0

### Added

- Support for async-aws/core 1.0.

## 0.4.0

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- The `AsyncAws\Sqs\Enum\*`, `AsyncAws\Sqs\Input\*` and `AsyncAws\Sqs\ValueObject*` classes are marked final.

## 0.3.0

### Added

- Enums; `QueueAttributeName`

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
