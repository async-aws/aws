# Change Log

## 0.5.0

### Changed

- The `StreamableBodyInterface::getChunks` now returns a iterrable of string.
- The `AsyncAws\Core\Sts\Input\*` and `AsyncAws\Core\Sts\ValueObject*` classes are marked final.

## 0.4.0

### Added

- Test class `AsyncAws\Core\Test\SimpleStreamableBody`

### Changed

- Moved `AsyncAws\Core\Signer\Request` to `AsyncAws\Core\Request`.
- Added constructor argument to  `AsyncAws\Core\Request::__construct()` to support query parameters.
- Renamed `AsyncAws\Core\Request::getUrl()` to `AsyncAws\Core\Request::getEndpoint()`
- Class `AsyncAws\Core\Stream\StreamFactory` is not internal anymore.
- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.

### Removed

- Public `AbstractApi::request()` was removed.
- Protected function `AbstractApi::getEndpoint()` was made private.

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
