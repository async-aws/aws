# Change Log

## NOT RELEASED

### Changed

- Remove redundant ext-json requirement

## 2.12.0

### Added

- Add `UNKNOWN_TO_SDK` value to enums that is used when the API returns an value that is not (or not yet) known by the AsyncAws

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.
- Use a more stable sorting for the list of generated region metadata

## 2.11.1

### Changed

- AWS enhancement: Documentation updates.

## 2.11.0

### Added

- AWS api-change: AWS CodeBuild now supports Docker Server capability

## 2.10.0

### Added

- AWS api-change: This release adds support for environment type WINDOWS_SERVER_2022_CONTAINER in ProjectEnvironment
- AWS api-change: Added `us-isob-east-1` region.
- AWS api-change: Add support for custom instance type for reserved capacity fleets

### Changed

- Sort exception alphabetically.

## 2.9.0

### Added

- AWS api-change: This release adds support for cacheNamespace in ProjectCache

### Changed

- AWS enhancement: Documentation updates.

## 2.8.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 2.7.0

### Added

- AWS api-change: Added `us-iso-east-1` and `us-iso-west-1` regions.

### Changed

- AWS enhancement: Documentation updates.

## 2.6.0

### Added

- AWS api-change: AWS CodeBuild now supports automatically retrying failed builds
- AWS api-change: AWS CodeBuild now adds additional compute types for reserved capacity fleet.
- AWS api-change: AWS CodeBuild now supports non-containerized Linux and Windows builds on Reserved Capacity.

## 2.5.0

### Added

- AWS api-change: AWS CodeBuild now supports using Secrets Manager to store git credentials and using multiple source credentials in a single project.
- AWS api-change: Add `MacArm` environment type

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.4.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers
- AWS enhancement: Documentation updates.

## 2.4.0

### Added

- AWS api-change: Supporting GitLab and GitLab Self Managed as source types in AWS CodeBuild.

### Changed

- AWS enhancement: Documentation updates.

## 2.3.0

### Added

- AWS api-change: Release CodeBuild Reserved Capacity feature

## 2.2.0

### Added

- AWS api-change: Aws CodeBuild now supports new compute type BUILD_GENERAL1_XLARGE

## 2.1.0

### Added

- AWS api-change: AWS CodeBuild now supports AWS Lambda compute.

### Changed

- Allow passing explicit null values for optional fields of input objects

## 2.0.0

### BC-BREAK

- The return type for `\AsyncAws\CodeBuild\ValueObject\Build::getBuildNumber` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\CodeBuild\ValueObject\BuildPhase::getDurationInSeconds` uses `int` instead of `string` to reflect the AWS type.

### Added

- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- Nothing changed. Let's release a First stable version

## 0.1.0

First version
