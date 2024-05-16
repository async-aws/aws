# Change Log

## NOT RELEASED

### Changed

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
