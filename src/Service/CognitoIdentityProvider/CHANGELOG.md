# Change Log

## NOT RELEASED

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
