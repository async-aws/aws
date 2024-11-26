# Change Log

## NOT RELEASED

## 2.6.0

### Added

- AWS api-change: Amazon Simple Storage Service / Features: Add support for ETag based conditional writes in PutObject and CompleteMultiPartUpload APIs to prevent unintended object modifications.

## 2.5.0

### Added

- AWS api-change: Add support for the new optional bucket-region and prefix query parameters in the ListBuckets API. For ListBuckets requests that express pagination, Amazon S3 will now return both the bucket names and associated AWS regions in the response.
- AWS api-change: Add support for conditional deletes for the S3 DeleteObject and DeleteObjects APIs. Add support for write offset bytes option used to append to objects with the S3 PutObject API.

### Changed

- use strict comparison `null !==` instead of `!`
- AWS enhancement: Documentation updates.

## 2.4.0

### Added

- AWS api-change: Amazon Simple Storage Service / Features : Adds support for pagination in the S3 ListBuckets API.
- AWS api-change: Amazon Simple Storage Service / Features : Add support for conditional writes for PutObject and CompleteMultipartUpload APIs.
- AWS api-change: Added `ap-southeast-5` region

### Changed

- Enable compiler optimization for the `sprintf` function.
- AWS enhancement: Documentation updates.

## 2.3.0

### Added

- AWS api-change: Added response overrides to Head Object requests.

### Fixed

- Exception NoSuchKeyException not fired for `HeadObject` request.

## 2.2.1

### Changed

- AWS enhancement: Documentation updates.

## 2.2.0

### Added

- AWS api-change: Added `fips-us-iso-east-1` region
- AWS api-change: Added `ca-west-1` and `fips-ca-west-1` regions

## 2.1.0

### Added

- AWS api-change: This release adds a new field COMPLETED to the ReplicationStatus Enum. You can now use this field to validate the replication status of S3 objects using the AWS SDK.
- Added `S3Client::uploadPartCopy()` method
- AWS api-change: Adds support for S3 Express One Zone.

### Changed

- Allow passing explicit null values for optional fields of input objects
- AWS enhancement: Documentation updates.

### Fixed

- Fix the format of the `objectLockRetainUntilDate` field in requests

## 2.0.0

### BC-BREAK

- The type for `\AsyncAws\S3\Input\PutObjectRequest::getContentLength` and `\AsyncAws\S3\Input\PutObjectRequest::setContentLength` uses `int` instead of `string` to reflect the AWS type.
- The type for `\AsyncAws\S3\Input\UploadPartRequest::getContentLength` and `\AsyncAws\S3\Input\UploadPartRequest::setContentLength` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\S3\Result\GetObjectOutput::getContentLength` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\S3\Result\HeadObjectOutput::getContentLength` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\S3\ValueObject\AwsObject::getSize` uses `int` instead of `string` to reflect the AWS type.
- The return type for `\AsyncAws\S3\ValueObject\Part::getSize` uses `int` instead of `string` to reflect the AWS type.

### Added

- AWS api-change: The S3 LISTObjects, ListObjectsV2 and ListObjectVersions API now supports a new optional header x-amz-optional-object-attributes. If header contains RestoreStatus as the value, then S3 will include Glacier restore status i.e. isRestoreInProgress and RestoreExpiryDate in List response.
- AWS api-change: Added `ap-south-2` and `eu-south-2` bucket location constraints.
- AWS api-change: Add support for the `il-central-1` region
- Use int as the PHP representation of long fields in generated code

### Changed

- Improve parameter type and return type in phpdoc

## 1.14.0

### Added

- AWS api-change: Added `ap-southeast-4` region.
- AWS enhancement: Documentation updates.
- AWS api-change: Provides support for "Snow" Storage class.
- AWS api-change: Integrate double encryption feature to SDKs.
- AWS api-change: This release adds SDK support for request-payer request header and request-charged response header in the "GetBucketAccelerateConfiguration", "ListMultipartUploads", "ListObjects", "ListObjectsV2" and "ListObjectVersions" S3 APIs.

## 1.13.0

### Added

- Added `me-central-1`, `ap-southeast-3`, `eu-central-2`, `eu-south-2` and `ap-south-2` regions

## 1.12.0

### Changed

- Set default value to `false` for the `sendChunkedBody` option.

### Fixed

- Format datetime with `RFC7231` to provide a workaround for unsupported `RFC822` format.
- Broken path to host when operation's URL contains a query string.

## 1.11.0

### Added

- AWS api-change: Added `ap-southeast-3` region.
- AWS enhancement: Documentation updates.
- AWS feature: Adds support for flexible checksums
- AWS api-change: This release adds support for new integrity checking capabilities in Amazon S3. You can choose from four supported checksum algorithms for data integrity checking on your upload and download requests. In addition, AWS SDK can automatically calculate a checksum as it streams data into S3

## 1.10.0

### Added

- AWS api-change: used unique endpoint for `accesspoint-*` regions
- AWS api-change: Rework regions configuration.
- AWS api-change: Introduce Amazon S3 Glacier Instant Retrieval storage class and a new setting in S3 Object Ownership to disable ACLs for bucket and the objects in it.
- AWS api-change: Amazon S3 Event Notifications adds Amazon EventBridge as a destination and supports additional event types. The PutBucketNotificationConfiguration API can now skip validation of Amazon SQS, Amazon SNS and AWS Lambda destinations.
- AWS api-change: Introduce two new Filters to S3 Lifecycle configurations - ObjectSizeGreaterThan and ObjectSizeLessThan. Introduce a new way to trigger actions on noncurrent versions by providing the number of newer noncurrent versions along with noncurrent days.

## 1.9.2

### Added

- AWS enhancement: Documentation updates for Amazon S3
- Fixed camelCased of Dom classes

## 1.9.1

### Fixed

- Fix issue when a request to upload a file is retried
- camelCased methods with paginator and waiter
- AWS enhancement: Documentation updates for Amazon S3

## 1.9.0

### Added

- AWS api-change: Adding ID element to the CORSRule schema
- AWS api-change: Adding many more regions

### Changed

- AWS api-change: Reword docblocks
- AWS enhancement: Amazon S3 Documentation updates
- AWS api-change: Improve documentation
- AWS enhancement: Documentation updates for Amazon S3

### Fixed

- Wrong custom encoding on chunked stream

## 1.8.0

### Added

- Changed case of object's properties to camelCase.
- Added documentation in class headers.
- Added `PutBucketCors`, `DeleteBucketCors` and `GetBucketCors` methods.
- Added domain exceptions

## 1.7.0

### Added

- AWS api-change: S3 adds support for multiple-destination replication, option to sync replica modifications; S3 Bucket Keys to reduce cost of S3 SSE with AWS KMS
- AWS api-change: Format GetObject's Expires header to be an http-date instead of iso8601
- Added support for `sendChunkedBody` option to enable/disabled chunked body.

## 1.6.0

### Added

- Added `S3Client::putBucketNotificationConfiguration()`
- AWS api-change: S3 Intelligent-Tiering adds support for Archive and Deep Archive Access tiers

### Changed

- Removed deprecation warning on Content-MD5 headers.

### Fixed

- Make sure we throw exception from async-aws/core

## 1.5.1

### Fixed

- Fixed endpoint issue when a bucket name started with a number.
- Improve StorageClass documentation.

## 1.5.0

### Added

- AWS api-change: Amazon S3 on Outposts expands object storage to on-premises AWS Outposts environments, enabling you to store and retrieve objects using S3 APIs and features.

## 1.4.0

### Added

- AWS api-change: Bucket owner verification feature added. This feature introduces the x-amz-expected-bucket-owner and x-amz-source-expected-bucket-owner headers.

## 1.3.0

### Added

- Support for PHP 8
- Added `S3Client::deleteBucket()`

### Deprecated

- `PutObjectAclRequest::getContentMD5()`
- `PutObjectAclRequest::setContentMD5()`

### Fixed

- Fixed an issue in Metadata not beeing sent to AWS in `PutObject`, `CopyObject` and `CreateMultipartUpload`
- Internal AWS prefix were added to Metadata's name in `GetObject` and `HeadObject`.

## 1.2.0

### Added

- Changed from "path  style" endpoints (https://amazon.com/bucket) to "host style" endpoints (https://bucket.amazon.com). To keep the old behavior, use `s3PathStyleEndpoint: true` configuration option.

### Deprecated

- Protected methods `getServiceCode`, `getSignatureVersion` and `getSignatureScopeName` of `S3Client` are deprecated and will be removed in 2.0

### Fixed

- Fixed issue when Bucket or Object's Key contained a special char like `#`

## 1.1.0

### Added

- Backported split request behavior in `SignerV4ForS3`

### Fixed

- Add return typehint for `listMultipartUploads`, `listObjectsV2` and `listParts`

## 1.0.0

### Added

- Support for async-aws/core 1.0.

## 0.4.0

### Removed

- Dependency on `symfony/http-client-contracts`
- All `validate()` methods on the inputs. They are merged with `request()`.

### Added

- Support for presign
- Multipart upload
- Waiters: `S3Client::bucketExists()` and `S3Client::objectExists()`
- The `AsyncAws\S3\Enum\*`, `AsyncAws\S3\Input\*` and `AsyncAws\S3\ValueObject*` classes are marked final.

### Changed

- Moved value objects to a dedicated namespace.
- Results' `populateResult()` has only one argument. It takes a `AsyncAws\Core\Response`.
- Using `DateTimeImmutable` instead of `DateTimeInterface`

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
