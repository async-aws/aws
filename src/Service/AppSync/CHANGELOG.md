# Change Log

## NOT RELEASED

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\AppSync\Input\UpdateApiKeyRequest::setExpires` and `\AsyncAws\AppSync\Input\UpdateApiKeyRequest::getExpires` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getDeletes` and `getExpires` of `\AsyncAws\AppSync\ValueObject\ApiKey` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\AppSync\ValueObject\CachingConfig::getTtl` uses `int` instead of `string` to reflect the AWS type.
- The return type for the methods `getBaseTableTtl` and `getDeltaSyncTableTtl` of `\AsyncAws\AppSync\ValueObject\DeltaSyncConfig` uses `int` instead of `string` to reflect the AWS type.
- The class `AsyncAws\AppSync\ValueObject\FunctionConfiguration` (not used by any supported operations) has been removed.

### Added

- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.1.0

### Added

- AWS api-change: This release introduces the feature to support EventBridge as AppSync data source.

## 1.0.0

### Added

- AWS api-change: `ttl` property is required in CreateResolverResponse, ListResolversResponse and UpdateResolverResponse
- AWS api-change: This release introduces the APPSYNC_JS runtime, and adds support for JavaScript in AppSync functions and AppSync pipeline resolvers.

## 0.1.1

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: AppSync: AWS AppSync now supports configurable batching sizes for AWS Lambda resolvers, Direct AWS Lambda resolvers and pipeline functions

## 0.1.0

First version
