# Change Log

## NOT RELEASED

## 1.2.0

### Added

- Support for EventBridge in `AwsClientFactory`
- Support for IAM in `AwsClientFactory`
- Add a `PsrCacheProvider` and `SymfonyCacheProvider` to persists crendentials in a cache pool
- Add a `Credential::adjustExpireDate` method for adjusting the time according to the time difference with AWS clock
- Support for global and regional endpoints
- Add a `Configuration::optionExists` to allow third parties to check if an option is available (needed by libraries supporting several versions of core)

### Deprecation

- Clients extending `AbstractApi` should override `getEndpointMetata`. The method will be abstract in 2.0
- Custom endpoints should not contain `%region%` and `%service` placeholder. They won't be replaced anymore in 2.0
- Protected methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` of AbstractApi are deprecated and will be removed in 2.0

### Fixed

- Fix signing of requests with a header containing a date (like `expires` in `S3`).
- Fix thread safety regarding env vars by using `$_SERVER` instead of `getenv()`.

## 1.1.0

### Added

- Support for ECS Credentials Provider
- Support for Cognito Identity Provider client in `AwsClientFactory`
- Support for Cloud Watch Log client in `AwsClientFactory`

### Fixed

- Fixed invalid chunking of request with large body for most clients but S3. This version removed the invalid code from SignerV4 to make sure requests are not chunked.
- Use camelCase for all getter methods.

## 1.0.0

### Added

- Support for CodeDeploy client in `AwsClientFactory`

### Fixed

- Handle Aws Error type in JsonRest error responses

## 0.5.4

### Added

- Logging on HTTP exceptions.

## 0.5.3

### Added

- Support for SSM client in `AwsClientFactory`
- Support for Waiters in `ResultMockFactory`

## 0.5.2

### Fixed

- Add support for `Content-Type: application/x-amz-json-1.1` in test case.

## 0.5.1

### Added

- Add `Configuration::isDefault` methods.

### Fixed

- Allow mocking of Results classes named "*Result"

## 0.5.0

### Added

- Add support for multiregion via `@region` input parameter.
- DynamoDB support.
- `ResultMockFactory` was updated with `createFailing()` and support for pagination.
- `AbstractApi::presign()`.
- `Result::wait()` for multiplexing downloads.
- Interface `AsyncAws\Core\Input`.
- `AsyncAws\Core\Stream\ResponseBodyResourceStream` and `AsyncAws\Core\Stream\ResponseBodyStream`.
- Internal `AsyncAws\Core\Response` to encapsulate the HTTP client.
- Internal `AsyncAws\Core\RequestContext`.
- Internal `AsyncAws\Core\Stream\RewindableStream`.

### Removed

- The input's `validate()` function was merged with the `request()` function.
- `Configuration::isDefault()`.
- Protected property `AbstractApi::$logger`.
- `AsyncAws\Core\StreamableBody` in favor of `AsyncAws\Core\Stream\ResponseBodyStream`.

### Changed

- Exceptions will contain more information from the HTTP response.
- Moved STS value objects to a dedicated namespace.
- The `AsyncAws\Core\Sts\Input\*` and `AsyncAws\Core\Sts\ValueObject*` classes are marked final.
- Using `DateTimeImmutable` instead of `DateTimeInterface`.
- Protected properties `AbstractApi::$httpClient`, `AbstractApi::$configuration` and `AbstractApi::$credentialProvider` are now private.
- `AbstractApi::getResponse()` has new signature. New optional second argument `?RequestContext $context = null` and the return type is `AsyncAws\Core\Response`.
- The `CredentialProvider`s and `Configuration` are now `final`.
- Renamed `AsyncAws\Core\Stream\Stream` to `AsyncAws\Core\Stream\RequestStream`.
- Renamed `AsyncAws\Core\StreamableBodyInterface` to `AsyncAws\Core\Stream\ResultStream`.
- The `ResultStream::getChunks()` now returns a iterable of string.

### Fixed

- Bugfix in `WebIdentityProvider`

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
