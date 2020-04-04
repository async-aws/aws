# Change Log

## 0.4.0

### Added

- Support for presign
- Multipart upload
- Waiters: `S3Client::bucketExists()` and `S3Client::objectExists()`

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- Using `DateTimeImmutable` instead of `DateTimeInterface`

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

## 0.3.0

### Added

- Enums; `BucketCannedACL`, `BucketLocationConstraint`, `EncodingType`, `MetadataDirective`, `ObjectCannedACL`, `ObjectLockLegalHoldStatus`
  `ObjectLockMode`, `ObjectStorageClass`, `Permission`, `ReplicationStatus`, `RequestCharged`, `RequestPayer`, `ServerSideEncryption`
  `StorageClass`, `TaggingDirective`, `Type`

### Changed

- Removed `requestBody()`, `requestHeaders()`, `requestQuery()` and `requestUri()` input classes. They are replaced with `request()`.
- Using async-aws/core: 0.4.0

### Fixed

- Dont generate a request body when no body is needed.

## 0.2.0

### Changed

- Using async-aws/core: 0.3.0

## 0.1.0

First version
