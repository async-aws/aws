# Change Log

## NOT RELEASED

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
