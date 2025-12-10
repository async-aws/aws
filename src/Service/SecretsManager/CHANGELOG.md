# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added `us-isob-west-1` region
- AWS api-change: Adds support to create, update, retrieve, rotate, and delete managed external secrets.
- AWS api-change: TODO

### Dependency bumped

- Drop support for PHP versions lower than 8.2

### Changed

- Apply new CodingStandard from latest php-cs-fixer.

## 2.9.0

### Added

- AWS api-change: Rework regions configuration

## 2.8.0

### Added

- AWS api-change: added `eu-isoe-west-1` region

### Changed

- Sort exception alphabetically.

### Fixed

- Fix the lowest bound for the `symfony/polyfill-uuid` requirement

## 2.7.0

### Added

- AWS api-change: Fix `fips` and `isob` regions name

## 2.6.0

### Added

- AWS api-change: Added `us-isof-east-1`  and `us-isof-south-1` regions

## 2.5.0

### Added

- AWS api-change: Added `ap-southeast-7` region
- AWS api-change: Added `mx-central-1` region

## 2.4.1

### Changed

- use strict comparison `null !==` instead of `!`

## 2.4.0

### Added

- AWS api-change: Added `ap-southeast-5` region

### Changed

- AWS enhancement: Documentation updates.

## 2.3.0

### Added

- AWS api-change: Introducing RotationToken parameter for PutSecretValue API

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers
- AWS enhancement: Documentation updates.

## 2.2.2

### Changed

- AWS enhancement: Documentation updates.

## 2.2.1

### Changed

- AWS enhancement: Documentation updates.

## 2.2.0

### Added

- AWS api-change: Added `ca-west-1` and `fips-ca-west-1` regions

## 2.1.0

### Added

- AWS api-change: Add additional InvalidRequestException to list of possible exceptions for ListSecret.
- AWS api-change: Change region's endpoints.

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\SecretsManager\Input\DeleteSecretRequest::getRecoveryWindowInDays` and `\AsyncAws\SecretsManager\Input\DeleteSecretRequest::setRecoveryWindowInDays` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\SecretsManager\ValueObject\RotationRulesType::getAutomaticallyAfterDays` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS enhancement: Documentation updates.
- Avoid overriding the exception message with the raw message
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.0.0

### Added

- AWS enhancement: Documentation updates.
- AWS api-change: Added owning service filter, include planned deletion flag, and next rotation date response parameter in ListSecrets.
- AWS api-change: Added `us-iso-west-1` and `us-isob-east-1` regions

## 0.1.3

### Added

- AWS enhancement: Documentation updates.

## 0.1.2

### Added

- AWS enhancement: Documentation updates for Secrets Manager
- AWS api-change: Use specific configuration for `us` regions
- AWS enhancement: Documentation updates.
- AWS api-change: Added `Duration` and `ScheduleExpression` to the Rotation rules
- AWS api-change: Added `ca-central-1-fips` region

## 0.1.1

### Added

- Fill the optional `clientRequestToken` property with an UUID when value is omitted.
- AWS enhancement: Documentation updates for SecretsManager.

## 0.1.0

First version
