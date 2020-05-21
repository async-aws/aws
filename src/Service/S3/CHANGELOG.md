# Change Log

## NOT RELEASED

## 1.2.0

### Added

- Changed from "path  style" endpoints (https://amazon.com/bucket) to "host style" endpoints (https://bucket.amazon.com). To keep the old behavior, use `s3PathStyleEndpoint: true` configuration option.

### Fixed

- Fixed issue when Bucket or Object's Key contained a special char like `#`

### Deprecation

- Protected methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` of `S3Client` are deprecated and will be removed in 2.0

## 1.1.0

### Added

- Backported split request behavior in `SignerV4ForS3`

### Fixed

- Add return typehint for `listMultipartUploads`, `listObjectsV2` and `listParts`

## 1.0.0

### Added

- Support for async-aws/core 1.0.

## 0.4.0

### Added

- Support for presign
- Multipart upload
- Waiters: `S3Client::bucketExists()` and `S3Client::objectExists()`
- The `AsyncAws\S3\Enum\*`, `AsyncAws\S3\Input\*` and `AsyncAws\S3\ValueObject*` classes are marked final.

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
