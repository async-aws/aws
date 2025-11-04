# Change Log

## NOT RELEASED

### Added

- AWS api-change: Add NodeJs 24 (nodejs24.x) support to AWS Lambda.
- AWS api-change: Added `us-isob-west-1` region
- AWS api-change: Added SerializedRequestEntityTooLargeException to Lambda Invoke API
- AWS api-change: Add Python3.14 (python3.14) and Java 25 (java25) support to AWS Lambda

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 2.12.0

### Added

- AWS api-change: Rework regions configuration
- AWS api-change: remove trailling `/` in `ListFunctions` request

### Changed

- AWS enhancement: Documentation updates.

## 2.11.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

## 2.10.0

### Added

- AWS api-change: Add Ruby 3.4 (ruby3.4) support to AWS Lambda.

## 2.9.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 2.8.0

### Added

- AWS api-change: Added `ap-southeast-7` region
- AWS api-change: Added `mx-central-1` region

## 2.7.0

### Added

- AWS api-change: Add Python 3.13 (python3.13) support to AWS Lambda
- AWS api-change: Add Node 22.x (node22.x) support to AWS Lambda

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 2.6.0

### Added

- AWS api-change: Added `ap-southeast-5` region

### Changed

- Enable compiler optimization for the `sprintf` function.
- AWS enhancement: Documentation updates.

## 2.5.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.5.0

### Added

- AWS api-change: Add Ruby 3.3 (ruby3.3) support to AWS Lambda

### Changed

- AWS enhancement: Documentation updates.

## 2.4.0

### Added

- AWS api-change: Add .NET 8 (dotnet8) Runtime support to AWS Lambda.

### Changed

- AWS enhancement: Documentation updates.

## 2.3.0

### Added

- AWS api-change: Added `ca-west-1` region

## 2.2.0

### Added

- AWS api-change: Adds support for Lambda functions to access Dual-Stack subnets over IPv6, via an opt-in flag in CreateFunction and UpdateFunctionConfiguration APIs
- AWS api-change: Adds support for logging configuration in Lambda Functions. Customers will have more control how their function logs are captured and to which cloud watch log group they are delivered also.
- AWS api-change: Add Java 21 (java21) support to AWS Lambda
- AWS api-change: Add Python 3.12 (python3.12) support to AWS Lambda
- AWS api-change: Add Custom runtime on Amazon Linux 2023 (provided.al2023) support to AWS Lambda.
- AWS api-change: Add Node 20 (nodejs20.x) support to AWS Lambda.

## 2.1.0

### Added

- Added operation `getFunctionConfiguration` and `updateFunctionConfiguration`.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest::getVersionNumber` and `\AsyncAws\Lambda\Input\AddLayerVersionPermissionRequest::setVersionNumber` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Lambda\Result\PublishLayerVersionResponse::getVersion` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Lambda\ValueObject\FunctionConfiguration::getCodeSize` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Lambda\ValueObject\Layer::getCodeSize` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Lambda\ValueObject\LayerVersionContentOutput::getCodeSize` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\Lambda\ValueObject\LayerVersionsListItem::getVersion` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS api-change: This release adds RecursiveInvocationException to the Invoke API and InvokeWithResponseStream API.
- AWS enhancement: Documentation updates.
- AWS api-change: Add Python 3.11 (python3.11) support to AWS Lambda
- AWS api-change: Add support for the `il-central-1` region
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.9.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Release Lambda RuntimeManagementConfig, enabling customers to better manage runtime updates to their Lambda functions. This release adds two new APIs, GetRuntimeManagementConfig and PutRuntimeManagementConfig, as well as support on existing Create/Get/Update function APIs.
- AWS api-change: Added `ap-southeast-4` region.
- AWS api-change: Add Python 3.10 (python3.10) support to AWS Lambda
- AWS api-change: Add Ruby 3.2 (ruby3.2) Runtime support to AWS Lambda.
- AWS api-change: Add Java 17 (java17) support to AWS Lambda

## 1.8.0

### Added

- AWS api-change: Restrict the list of available regions.
- AWS api-change: Added `eu-central-2`, `eu-south-2` and `ap-south-2` regions
- AWS api-change: Add Node 18 (nodejs18.x) support to AWS Lambda.
- AWS api-change: Adds support for Lambda SnapStart, which helps improve the startup performance of functions. Customers can now manage SnapStart based functions via CreateFunction and UpdateFunctionConfiguration APIs

## 1.7.0

### Added

- AWS api-change: Adds support for increased ephemeral storage (/tmp) up to 10GB for Lambda functions. Customers can now provision up to 10 GB of ephemeral storage per function instance, a 20x increase over the previous limit of 512 MB.
- AWS api-change: Added NodeJs 16 managed runtime
- Lambda is available in all commercial regions

## 1.6.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- AWS api-change: Remove Lambda function url apis
- AWS api-change: Release Lambda event source filtering for SQS, Kinesis Streams, and DynamoDB Streams.
- AWS api-change: Add support for Lambda Function URLs. Customers can use Function URLs to create built-in HTTPS endpoints on their functions.
- AWS api-change: Added support for CLIENT_CERTIFICATE_TLS_AUTH and SERVER_ROOT_CA_CERTIFICATE as SourceAccessType for MSK and Kafka event source mappings.
- AWS api-change: Lambda releases .NET 6 managed runtime to be available in all commercial regions.

## 1.5.0

### Added

- AWS api-change: Lambda Python 3.9 runtime launch
- AWS api-change: Adds support for Lambda functions powered by AWS Graviton2 processors. Customers can now select the CPU architecture for their functions.

### Changed

- AWS enhancement: Documentation updates for Amazon Lambda.

### Fixed

- Assert the provided Input can be json-encoded.

## 1.4.0

### Added

- AWS api-change: Added constant for NodeJs 14.
- Added operation `deleteFunction`, `listFunctions`, and `listVersionsByFunction`.

## 1.3.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.2.0

### Added

- AWS api-change: This release includes support for new feature: Code Signing for AWS Lambda. This adds new resources and APIs to configure Lambda functions to accept and verify signed code artifacts at deployment.

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.1.1

### Fixed

- Make sure we throw exception from async-aws/core

## 1.1.0

### Added

- Added constants `JAVA_8_AL_2` and `PROVIDED_AL_2` for `Runtime` enum.

## 1.0.0

### Added

- Support for PHP 8

## 0.5.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Fixed issue when Layer, Function or Version contained a special char `#`
- Add return typehint for `listLayerVersions`

## 0.4.1

### Changed

- Support only version 1.0 of async-aws/core

## 0.4.0

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- Using `DateTimeImmutable` instead of `DateTimeInterface`
- The `AsyncAws\Lambda\Enum\*`, `AsyncAws\Lambda\Input\*` and `AsyncAws\Lambda\ValueObject*` classes are marked final.

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
