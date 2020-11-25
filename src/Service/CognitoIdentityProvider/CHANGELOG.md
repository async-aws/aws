# Change Log

## NOT RELEASED

### Added

- AWS api-change: Added regions eu-north-1and eu-west-3.
- AWS api-change: Improve documentation about configuring Cognito User Pools with third party sms and email providers.

### Fixed

- Make sure required Map properties are validated before sending the request
- Make sure empty Map properties are converted to `{}` in Json request.

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
