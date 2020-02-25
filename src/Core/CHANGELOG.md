# Change Log

## 0.2.0

### Added

- Class `AsuncAws\Core\Credentials\NullProvider`
- Methods `AwsClient::cloudFormation()`, `AwsClient::lambda()`, `AwsClient::sns()`
- Protected methods `Result::registerPrefetch()` and `Result::unregisterPrefetch()`
- Timeout parameter to `InstanceProvider::__construct()`

### Changed

- Removed `AwsClient` and replaced it with `AwsClientFactory`
- Class `AsuncAws\Core\Signers\Request` is marked as internal
- Make sure behavior of calling `Result::resolve()` is consistent.

## 0.1.0

First version
