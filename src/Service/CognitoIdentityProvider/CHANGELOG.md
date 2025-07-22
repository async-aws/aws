# Change Log

## NOT RELEASED

### Added

- AWS api-change: Rework regions configuration

## 1.14.0

### Added

- AWS api-change: methods `adminGetUser`, `adminInitiateAuth`, `getUser` and `initiateAuth` might throw an `UnsupportedOperationException`.

### Changed

- Sort exception alphabetically.

## 1.13.0

### Added

- AWS api-change: Added `ap-southeast-5` and `us-gov-east-1` regions

### Changed

- AWS enhancement: Documentation updates.

## 1.12.0

### Added

- AWS api-change: Added the capacity to return available challenges in admin authentication and to set version 3 of the pre token generation event for M2M ATC.

## 1.11.0

### Added

- AWS api-change: use explicit defined regions

### Changed

- AWS enhancement: Documentation updates.

## 1.10.0

### Added

- AWS api-change: Add support for users to sign up and sign in without passwords, using email and SMS OTPs and Passkeys. Add support for Passkeys based on WebAuthn. Add support for enhanced branding customization for hosted authentication pages with Amazon Cognito Managed Login. Add feature tiers with new pricing.

### Changed

- use strict comparison `null !==` instead of `!`

## 1.9.0

### Added

- AWS api-change: Added `PasswordHistoryPolicyViolationException` exception.
- AWS api-change: Added email MFA option to user pools with advanced security features.

### Changed

- Enable compiler optimization for the `sprintf` function.
- AWS enhancement: Documentation updates.

## 1.8.1

### Changed

- Add `Accept: application/json` header in request to fix incompatibility with 3rd party providers

## 1.8.0

### Added

- AWS api-change: Add LimitExceededException to SignUp errors
- AWS api-change: Add EXTERNAL_PROVIDER enum value to UserStatusType.

### Changed

- AWS enhancement: Documentation updates.

## 1.7.3

### Changed

- AWS enhancement: Documentation updates.

## 1.7.2

### Changed

- AWS enhancement: Documentation updates.

## 1.7.1

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

## 1.7.0

### Added

- AWS enhancement: Documentation updates.
- Avoid overriding the exception message with the raw message

### Changed

- Improve parameter type and return type in phpdoc

## 1.6.0

### Added

- Added operation `AdminDisableUser`
- Added operation `AdminEnableUser`
- AWS api-change: Add a new exception type, ForbiddenException, that is returned when request is not allowed
- Added operation `AdminAddUserToGroup`
- Added operation `AdminRemoveUserFromGroup`
- Added operation `CreateGroup`
- Added operation `ListGroups`
- Added operation `RevokeToken`

## 1.5.0

### Added

- AWS api-change: Amazon Cognito now supports IP Address propagation for all unauthenticated APIs (e.g. SignUp, ForgotPassword).
- AWS enhancement: Documentation updates.
- Added operation `confirmSignUp`

## 1.4.0

### Added

- AWS api-change: Use specific configuration for `us` regions
- Added operation `adminResetUserPassword`
- AWS enhancement: Documentation updates.
- Added operation `adminUserGlobalSignOut`

## 1.3.0

### Added

- AWS api-change: Added `fips-us-west-1` region
- AWS enhancement: Documentation updates for cognito-idp
- Added operation `getUser`

### Fixed

- Assert the provided Input can be json-encoded.

## 1.2.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added domain exceptions.

## 1.1.0

### Added

- AWS api-change: Added regions eu-north-1 and eu-west-3.

### Fixed

- AWS api-change: Improve documentation about configuring Cognito User Pools with third party sms and email providers.
- Make sure required Map properties are validated before sending the request
- Make sure empty Map properties are converted to `{}` in Json request.
- If provided an unrecognized region, then fallback to default region config

## 1.0.0

### Fixed

- Make sure we throw exception from async-aws/core

## 0.2.2

### Added

- Added operation `adminConfirmSignUp`
- Added operation `adminSetUserPassword`

## 0.2.1

### Added

- Added operation `adminInitiateAuth`
- Added operation `signUp`
- Added operation `initiateAuth`
- Added operation `respondToAuthChallenge`
- Added operation `forgotPassword`
- Added operation `confirmForgotPassword`
- Added operation `resendConfirmationCode`

## 0.2.0

### Removed

- Removes methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` from Client.

### Added

- Add return typehint for `listUsers`

## 0.1.0

First version
