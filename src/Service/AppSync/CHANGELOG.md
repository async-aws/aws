# Change Log

## NOT RELEASED

### Added

- AWS api-change: This release introduces the feature to support EventBridge as AppSync data source.
- AWS api-change: Fixes the URI for the evaluatecode endpoint to include the /v1 prefix (ie. "/v1/dataplane-evaluatecode").
- AWS api-change: This release introduces the APPSYNC_JS runtime, and adds support for JavaScript in AppSync functions and AppSync pipeline resolvers.
- AWS api-change: Adds support for a new API to evaluate mapping templates with mock data, allowing you to remotely unit test your AppSync resolvers and functions.
- AWS api-change: AppSync: AWS AppSync now supports configurable batching sizes for AWS Lambda resolvers, Direct AWS Lambda resolvers and pipeline functions
- AWS api-change: AWS AppSync now supports custom domain names, allowing you to associate a domain name that you own with an AppSync API in your account.

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
