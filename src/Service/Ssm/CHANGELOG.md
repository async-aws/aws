# Change Log

## NOT RELEASED

### Added

- AWS api-change: Support of AmazonLinux2022 by Patch Manager
- AWS api-change: This release includes support for applying a CloudWatch alarm to Systems Manager capabilities like Automation, Run Command, State Manager, and Maintenance Windows.
- AWS api-change: This release adds new SSM document types ConformancePackTemplate and CloudFormation
- AWS api-change: This release adds support for Systems Manager State Manager Association tagging.
- AWS enhancement: Adding doc updates for OpsCenter support in Service Setting actions.
- AWS api-change: This release adds the TargetMaps parameter in SSM State Manager API.
- AWS api-change: Update the StartChangeRequestExecution, adding TargetMaps to the Runbook parameter
- AWS api-change: Added offset support for specifying the number of days to wait after the date and time specified by a CRON expression when creating SSM association.
- AWS api-change: This Patch Manager release supports creating, updating, and deleting Patch Baselines for Rocky Linux OS.
- AWS api-change: Update AddTagsToResource, ListTagsForResource, and RemoveTagsFromResource APIs to reflect the support for tagging Automation resources. Includes other minor documentation updates.
- AWS enhancement: Assorted ticket fixes and updates for AWS Systems Manager.
- AWS enhancement: Documentation updates for AWS Systems Manager.
- AWS api-change: AWS Systems Manager adds category support for DescribeDocument API
- AWS api-change: Added two new attributes to DescribeInstanceInformation called SourceId and SourceType along with new string filters SourceIds and SourceTypes to filter instance records.
- AWS api-change: Adds new parameter to CreateActivation API . This parameter is for "internal use only".

## 1.3.0

### Added

- AWS api-change: Added `ca-central-1` region
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- Added operation `DeleteParameters`

## 1.2.1

### Fixed

- Assert the provided Input can be json-encoded.
- AWS enhancement: Documentation updates for AWS Systems Manager.

## 1.2.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- AWS enhancement: Added region "us-iso-east-1".
- Added domain exceptions

## 1.1.1

### Fixed

- Make sure we throw exception from async-aws/core
- If provided an unrecognized region, then fallback to default region config
- AWS api-change: Improve documentation for `GetParametersByPathRequest::$Path`.

## 1.1.0

### Added

- AWS api-change: Added region `fips-ca-central-1`

### Removed

The following regions are no longer supported by AWS and has been remove from
the client: `ssm-facade-fips-us-east-1`, `ssm-facade-fips-us-east-2`, `ssm-facade-fips-us-gov-east-1`,
`ssm-facade-fips-us-gov-west-1`, `ssm-facade-fips-us-west-1`, `ssm-facade-fips-us-west-2`.

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Changed

- parameter `type` of `putParameter` is optional.

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.1.2

### Fixed

- Add return typehint for `getParametersByPath`

## 0.1.1

### Changed

- Support only version 1.0 of async-aws/core

## 0.1.0

First version
