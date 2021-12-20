# Change Log

## NOT RELEASED

## 1.6.0

### Added

- Support for AppSync
- Support for XRay

## 1.5.0

### Added

- Support for CloudWatch
- Support for ElastiCache
- Support for Firehose
- Support for Kinesis
- Support for SecretsManager
- Support for StepFunctions

## 1.4.0

### Added

- Support for Symfony 6
- Support for Route 53

## 1.3.0

### Added

- The `async_aws.credential.cache` service will log cache failures.

## 1.2.0

### Added

- Support for ECR client.

### Changed

- Added channel "async_aws" to Monolog logger.

## 1.1.1

### Fixed

- Make sure you can override config in different environments.

## 1.1.0

### Added

- Support for Rekognition.

## 1.0.0

### Added

- Support for PHP 8

## 0.2.6

### Added

- Caching of credentials fetched by Clients

## 0.2.5

### Added

- Support for EventBridge.
- Support for IAM.
- Caching of SSM parameters.

## 0.2.4

### Added

- Support for Cognito Identity Provider.
- Support for CloudWatch Logs.

## 0.2.3

### Changed

- Support only version 1.0 of async-aws/core

## 0.2.2

### Added

- Support for loading environment variables from SSM

### Fixed

- Config when client config override default config.

## 0.2.1

### Added

- Support for async-aws/core: ^0.4 and ^0.5
- Support for DynamoDb

## 0.2.0

### Added

- Support for async-aws/core: ^0.3
- Adding VarDumpers

## 0.1.1

### Added

- Support for async-aws/core: ^0.2
- Support for CloudFormation, Lambda and SNS

### Fixed

- Configuration bug where you specified `async_aws.client:` and nothing more

## 0.1.0

First version
