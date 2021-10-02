# Change Log

## NOT RELEASED

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

### Deprecation

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

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- The `AsyncAws\Sqs\Enum\*`, `AsyncAws\Sqs\Input\*` and `AsyncAws\Sqs\ValueObject*` classes are marked final.

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

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
