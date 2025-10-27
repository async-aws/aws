# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added `us-isob-west-1` region

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 2.3.0

### Added

- AWS api-change: Added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

## 2.2.0

### Added

- AWS api-change: Added `us-isof-east-1` and `us-isof-south-1` regions

## 2.1.3

### Changed

- Enable compiler optimization for the `sprintf` function.

## 2.1.2

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 2.1.1

### Changed

- AWS enhancement: Documentation updates.

## 2.1.0

### Added

- AWS api-change: Add `AUTOSCALING_TERMINATION` const to `DeploymentCreator`.

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 2.0.0

### BC-BREAK

- The return type for the methods `getFailed`, `getInProgress`, `getPending`, `getReady`, `getSkipped` and `getSucceeded` of `\AsyncAws\CodeDeploy\ValueObject\DeploymentOverview` uses `int` instead of `string` to reflect the AWS type.

### Added

- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.4.0

### Added

- AWS api-change: This release allows you to override the alarm configurations when creating a deployment.

## 1.3.0

### Added

- AWS api-change: Added `us-iso-west-1` region
- AWS api-change: Use specific configuration for `us` regions
- Added `createDeployment` method
- Added `getDeployment` method

## 1.2.1

### Fixed

- AWS api-change: Improve documentation
- Assert the provided Input can be json-encoded.

## 1.2.0

### Added

- Added documentation in class headers.
- Added domain exceptions.

## 1.1.1

### Fixed

- If provided an unrecognized region, then fallback to default region config

## 1.1.0

### Added

- Added region us-isob-east-1

## 1.0.0

### Added

- Support for PHP 8

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

## 0.1.1

### Fixed

- Use camelCase for all getter methods.

## 0.1.0

First version
