# Change Log

## NOT RELEASED

### BC-BREAK

- The type for `\AsyncAws\SecretsManager\Input\DeleteSecretRequest::getRecoveryWindowInDays` and `\AsyncAws\SecretsManager\Input\DeleteSecretRequest::setRecoveryWindowInDays` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\SecretsManager\ValueObject\RotationRulesType::getAutomaticallyAfterDays` uses `int` instead of `string` to reflect the AWS type.

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
