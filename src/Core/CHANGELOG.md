# Change Log

## 0.3.4

### Fixed

- Fix Instance Provider Role fetching

## 0.3.3

### Added

- Added a `ResultMockFactory` to helps creating tests

### Fixed

- Http method is replaced by PUT in REST calls

## 0.3.2

### Fixed

- `Configuration` don't mix anymore attributes injected by php array and env variables.

## 0.3.1

### Added

- `AbstractApi::getConfiguration()`

### Fixed

- Make sure `Configuration::create(['foo'=>null])` is using the default value of "foo".

## 0.3.0

### Added

- Requests can now be streamed
- Streamable request accepts iterable alongside string, callable, resource
- Support for getting credentials from Web Identity or OpenID Connect Federation. (`WebIdentityProvider`)

### Changed

- Rename namespace `Signers` into `Signer`.

## 0.2.0

### Added

- Class `AsuncAws\Core\Credentials\NullProvider`
- Methods `AwsClient::cloudFormation()`, `AwsClient::lambda()`, `AwsClient::sns()`
- Protected methods `Result::registerPrefetch()` and `Result::unregisterPrefetch()`
- Timeout parameter to `InstanceProvider::__construct()`

### Changed

- Removed `AwsClient` and replaced it with `AwsClientFactory`
- Class `AsyncAws\Core\Signer\Request` is marked as internal
- Make sure behavior of calling `Result::resolve()` is consistent

## 0.1.0

First version
