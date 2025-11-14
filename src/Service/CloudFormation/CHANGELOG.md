# Change Log

## NOT RELEASED

### Added

- AWS api-change: Update endpoint ruleset parameters casing
- AWS apu-change: Added `us-isob-west-1` region
- AWS api-change: CloudFormation now supports GetHookResult API with annotations to retrieve structured compliance check results and remediation guidance for each evaluated resource, replacing the previous single-message limitation with detailed validation outcomes.

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- AWS enhancement: Documentation updates.
- Apply new CodingStandard from latest php-cs-fixer.

## 1.10.0

### Added

- AWS api-change: ListHookResults API now supports retrieving invocation results for all CloudFormation Hooks (previously limited to create change set and Cloud Control operations) with new optional parameters for filtering by Hook status and ARN.

## 1.9.1

### Changed

- AWS enhancement: Documentation updates.

## 1.9.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Normalize the composer requirements

## 1.8.1

### Changed

- AWS enhancement: Documentation updates.

## 1.8.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions
- AWS api-change: Added `ResourceStatus` constants for new StackRefactor apis.

## 1.7.2

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 1.7.1

### Changed

- Enable compiler optimization for the `sprintf` function.

## 1.7.0

### Added

- AWS api-change: Added DeletionMode FORCE_DELETE_STACK for deleting a stack that is stuck in DELETE_FAILED state due to resource deletion failure.

## 1.6.0

### Added

- AWS api-change: Add DetailedStatus field to DescribeStackEvents and DescribeStacks APIs

### Changed

- AWS enhancement: Documentation updates.

## 1.5.1

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 1.5.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: This SDK release is for the feature launch of AWS CloudFormation RetainExceptOnCreate. It adds a new parameter retainExceptOnCreate in the following APIs: CreateStack, UpdateStack, RollbackStack, ExecuteChangeSet.

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Added `us-gov-east-1-fips` and `us-gov-west-1-fips` regions

## 1.3.0

### Added

- AWS api-change: Add support to AWS CloudFormation Hooks.

## 1.2.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- Added `describeStackDriftDetectionStatus` method.

## 1.1.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- AWS api-change: AWS CloudFormation allows you to iteratively develop your applications when failures are encountered without rolling back successfully provisioned resources. By specifying stack failure options, you can troubleshoot resources in a CREATE_FAILED or UPDATE_FAILED status.

## 1.0.2

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.0.1

### Fixed

- Make sure we throw exception from async-aws/core

## 1.0.0

### Added

- Support for PHP 8

## 0.5.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Fixed

- Add return typehint for `describeStackEvents` and `describeStacks`

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
- The `AsyncAws\CloudFormation\Enum\*`, `AsyncAws\CloudFormation\Input\*` and `AsyncAws\CloudFormation\ValueObject*` classes are marked final.

## 0.3.0

### Added

- Enums; `Capability`, `ResourceStatus`, `StackDriftStatus`, `StackStatus`

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
