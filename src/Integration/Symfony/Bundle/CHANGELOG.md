# Change Log

## NOT RELEASED

## 1.15.0

### Added

- Support for Symfony 8

## 1.14.0

### Added

- Support for BedrockRuntime

## 1.13.0

### Added

- Support for SSOOIDC

## 1.12.3

### Changed

- Enable compiler optimization for the `sprintf` function.

### Fixed

- Fix bundle configuration with both `credential_provider` and `credential_provider_cache`.

## 1.12.2

### Changed

- Replace deprecated Extension by  Symfony\Component\DependencyInjection\Extension\Extension
  instead of the deprecated Symfony\Component\HttpKernel\DependencyInjection\Extension class

## 1.12.1

### Changed

- Adding `async-aws/s3` 2.0, `async-aws/sqs` 2.0, `async-aws/ssm` 2.0 in dev dependencies
- Adding `max_results` option in `secrets` configuration

## 1.12.0

### Added

- Support for LocationService
- Support for SSO

### Changed

- Improve parameter type and return type in phpdoc

## 1.11.0

### Added

- Support for Athena
- Support for MediaConvert

## 1.10.0

### Added

- Support for Scheduler

## 1.9.0

### Added

- Support for Iot Data

## 1.8.0

### Added

- Support for CodeBuild
- Support for CodeCommit
- Support for TimestreamQuery
- Support for TimestreamWrite
- Support for Iot Core

## 1.7.0

### Added

- Support for KMS

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
