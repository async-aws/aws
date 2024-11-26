<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;

final class PutObjectRequest extends Input
{
    /**
     * The canned ACL to apply to the object. For more information, see Canned ACL [^1] in the *Amazon S3 User Guide*.
     *
     * When adding a new object, you can use headers to grant ACL-based permissions to individual Amazon Web Services
     * accounts or to predefined groups defined by Amazon S3. These permissions are then added to the ACL on the object. By
     * default, all objects are private. Only the owner has full access control. For more information, see Access Control
     * List (ACL) Overview [^2] and Managing ACLs Using the REST API [^3] in the *Amazon S3 User Guide*.
     *
     * If the bucket that you're uploading objects to uses the bucket owner enforced setting for S3 Object Ownership, ACLs
     * are disabled and no longer affect permissions. Buckets that use this setting only accept PUT requests that don't
     * specify an ACL or PUT requests that specify bucket owner full control ACLs, such as the `bucket-owner-full-control`
     * canned ACL or an equivalent form of this ACL expressed in the XML format. PUT requests that contain other ACLs (for
     * example, custom grants to certain Amazon Web Services accounts) fail and return a `400` error with the error code
     * `AccessControlListNotSupported`. For more information, see Controlling ownership of objects and disabling ACLs [^4]
     * in the *Amazon S3 User Guide*.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-using-rest-api.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
     *
     * @var ObjectCannedACL::*|null
     */
    private $acl;

    /**
     * Object data.
     *
     * @var string|resource|(callable(int): string)|iterable<string>|null
     */
    private $body;

    /**
     * The bucket name to which the PUT action was initiated.
     *
     * **Directory buckets** - When you use this operation with a directory bucket, you must use virtual-hosted-style
     * requests in the format `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`. Path-style requests are not
     * supported. Directory bucket names must be unique in the chosen Availability Zone. Bucket names must follow the format
     * `*bucket_base_name*--*az-id*--x-s3` (for example, `*DOC-EXAMPLE-BUCKET*--*usw2-az1*--x-s3`). For information about
     * bucket naming restrictions, see Directory bucket naming rules [^1] in the *Amazon S3 User Guide*.
     *
     * **Access points** - When you use this action with an access point, you must provide the alias of the access point in
     * place of the bucket name or specify the access point ARN. When using the access point ARN, you must direct requests
     * to the access point hostname. The access point hostname takes the form
     * *AccessPointName*-*AccountId*.s3-accesspoint.*Region*.amazonaws.com. When using this action with an access point
     * through the Amazon Web Services SDKs, you provide the access point ARN in place of the bucket name. For more
     * information about access point ARNs, see Using access points [^2] in the *Amazon S3 User Guide*.
     *
     * > Access points and Object Lambda access points are not supported by directory buckets.
     *
     * **S3 on Outposts** - When you use this action with Amazon S3 on Outposts, you must direct requests to the S3 on
     * Outposts hostname. The S3 on Outposts hostname takes the form
     * `*AccessPointName*-*AccountId*.*outpostID*.s3-outposts.*Region*.amazonaws.com`. When you use this action with S3 on
     * Outposts through the Amazon Web Services SDKs, you provide the Outposts access point ARN in place of the bucket name.
     * For more information about S3 on Outposts ARNs, see What is S3 on Outposts? [^3] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-bucket-naming-rules.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-access-points.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/S3onOutposts.html
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Can be used to specify caching behavior along the request/reply chain. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9 [^1].
     *
     * [^1]: http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9
     *
     * @var string|null
     */
    private $cacheControl;

    /**
     * Specifies presentational information for the object. For more information, see
     * https://www.rfc-editor.org/rfc/rfc6266#section-4 [^1].
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc6266#section-4
     *
     * @var string|null
     */
    private $contentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field. For more information, see
     * https://www.rfc-editor.org/rfc/rfc9110.html#field.content-encoding [^1].
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc9110.html#field.content-encoding
     *
     * @var string|null
     */
    private $contentEncoding;

    /**
     * The language the content is in.
     *
     * @var string|null
     */
    private $contentLanguage;

    /**
     * Size of the body in bytes. This parameter is useful when the size of the body cannot be determined automatically. For
     * more information, see https://www.rfc-editor.org/rfc/rfc9110.html#name-content-length [^1].
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc9110.html#name-content-length
     *
     * @var int|null
     */
    private $contentLength;

    /**
     * The base64-encoded 128-bit MD5 digest of the message (without the headers) according to RFC 1864. This header can be
     * used as a message integrity check to verify that the data is the same data that was originally sent. Although it is
     * optional, we recommend using the Content-MD5 mechanism as an end-to-end integrity check. For more information about
     * REST request authentication, see REST Authentication [^1].
     *
     * > The `Content-MD5` or `x-amz-sdk-checksum-algorithm` header is required for any request to upload an object with a
     * > retention period configured using Amazon S3 Object Lock. For more information, see Uploading objects to an Object
     * > Lock enabled bucket [^2] in the *Amazon S3 User Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lock-managing.html#object-lock-put-object
     *
     * @var string|null
     */
    private $contentMd5;

    /**
     * A standard MIME type describing the format of the contents. For more information, see
     * https://www.rfc-editor.org/rfc/rfc9110.html#name-content-type [^1].
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc9110.html#name-content-type
     *
     * @var string|null
     */
    private $contentType;

    /**
     * Indicates the algorithm used to create the checksum for the object when you use the SDK. This header will not provide
     * any additional functionality if you don't use the SDK. When you send this header, there must be a corresponding
     * `x-amz-checksum-*algorithm*` or `x-amz-trailer` header sent. Otherwise, Amazon S3 fails the request with the HTTP
     * status code `400 Bad Request`.
     *
     * For the `x-amz-checksum-*algorithm*` header, replace `*algorithm*` with the supported algorithm from the following
     * list:
     *
     * - `CRC32`
     * - `CRC32C`
     * - `SHA1`
     * - `SHA256`
     *
     * For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * If the individual checksum value you provide through `x-amz-checksum-*algorithm*` doesn't match the checksum
     * algorithm you set through `x-amz-sdk-checksum-algorithm`, Amazon S3 ignores any provided `ChecksumAlgorithm`
     * parameter and uses the checksum algorithm that matches the provided value in `x-amz-checksum-*algorithm*`.
     *
     * > The `Content-MD5` or `x-amz-sdk-checksum-algorithm` header is required for any request to upload an object with a
     * > retention period configured using Amazon S3 Object Lock. For more information, see Uploading objects to an Object
     * > Lock enabled bucket [^2] in the *Amazon S3 User Guide*.
     *
     * For directory buckets, when you use Amazon Web Services SDKs, `CRC32` is the default checksum algorithm that's used
     * for performance.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lock-managing.html#object-lock-put-object
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 32-bit CRC-32 checksum of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 32-bit CRC-32C checksum of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 160-bit SHA-1 digest of the object. For more information,
     * see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the base64-encoded, 256-bit SHA-256 digest of the object. For more
     * information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * The date and time at which the object is no longer cacheable. For more information, see
     * https://www.rfc-editor.org/rfc/rfc7234#section-5.3 [^1].
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc7234#section-5.3
     *
     * @var \DateTimeImmutable|null
     */
    private $expires;

    /**
     * Uploads the object only if the ETag (entity tag) value provided during the WRITE operation matches the ETag of the
     * object in S3. If the ETag values do not match, the operation returns a `412 Precondition Failed` error.
     *
     * If a conflicting operation occurs during the upload S3 returns a `409 ConditionalRequestConflict` response. On a 409
     * failure you should fetch the object's ETag and retry the upload.
     *
     * Expects the ETag value as a string.
     *
     * For more information about conditional requests, see RFC 7232 [^1], or Conditional requests [^2] in the *Amazon S3
     * User Guide*.
     *
     * [^1]: https://tools.ietf.org/html/rfc7232
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/conditional-requests.html
     *
     * @var string|null
     */
    private $ifMatch;

    /**
     * Uploads the object only if the object key name does not already exist in the bucket specified. Otherwise, Amazon S3
     * returns a `412 Precondition Failed` error.
     *
     * If a conflicting operation occurs during the upload S3 returns a `409 ConditionalRequestConflict` response. On a 409
     * failure you should retry the upload.
     *
     * Expects the '*' (asterisk) character.
     *
     * For more information about conditional requests, see RFC 7232 [^1], or Conditional requests [^2] in the *Amazon S3
     * User Guide*.
     *
     * [^1]: https://tools.ietf.org/html/rfc7232
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/conditional-requests.html
     *
     * @var string|null
     */
    private $ifNoneMatch;

    /**
     * Gives the grantee READ, READ_ACP, and WRITE_ACP permissions on the object.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * @var string|null
     */
    private $grantFullControl;

    /**
     * Allows grantee to read the object data and its metadata.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * @var string|null
     */
    private $grantRead;

    /**
     * Allows grantee to read the object ACL.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * @var string|null
     */
    private $grantReadAcp;

    /**
     * Allows grantee to write the ACL for the applicable object.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * @var string|null
     */
    private $grantWriteAcp;

    /**
     * Object key for which the PUT action was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * Specifies the offset for appending data to existing objects in bytes. The offset must be equal to the size of the
     * existing object being appended to. If no object exists, setting this header to 0 will create a new object.
     *
     * > This functionality is only supported for objects in the Amazon S3 Express One Zone storage class in directory
     * > buckets.
     *
     * @var int|null
     */
    private $writeOffsetBytes;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>|null
     */
    private $metadata;

    /**
     * The server-side encryption algorithm that was used when you store this object in Amazon S3 (for example, `AES256`,
     * `aws:kms`, `aws:kms:dsse`).
     *
     * - **General purpose buckets ** - You have four mutually exclusive options to protect data using server-side
     *   encryption in Amazon S3, depending on how you choose to manage the encryption keys. Specifically, the encryption
     *   key options are Amazon S3 managed keys (SSE-S3), Amazon Web Services KMS keys (SSE-KMS or DSSE-KMS), and
     *   customer-provided keys (SSE-C). Amazon S3 encrypts data with server-side encryption by using Amazon S3 managed keys
     *   (SSE-S3) by default. You can optionally tell Amazon S3 to encrypt data at rest by using server-side encryption with
     *   other key options. For more information, see Using Server-Side Encryption [^1] in the *Amazon S3 User Guide*.
     * - **Directory buckets ** - For directory buckets, there are only two supported options for server-side encryption:
     *   server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys
     *   (SSE-KMS) (`aws:kms`). We recommend that the bucket's default encryption uses the desired encryption configuration
     *   and you don't override the bucket default encryption in your `CreateSession` requests or `PUT` object requests.
     *   Then, new objects are automatically encrypted with the desired encryption settings. For more information, see
     *   Protecting data with server-side encryption [^2] in the *Amazon S3 User Guide*. For more information about the
     *   encryption overriding behaviors in directory buckets, see Specifying server-side encryption with KMS for new object
     *   uploads [^3].
     *
     *   In the Zonal endpoint API calls (except CopyObject [^4] and UploadPartCopy [^5]) using the REST API, the encryption
     *   request headers must match the encryption settings that are specified in the `CreateSession` request. You can't
     *   override the values of the encryption settings (`x-amz-server-side-encryption`,
     *   `x-amz-server-side-encryption-aws-kms-key-id`, `x-amz-server-side-encryption-context`, and
     *   `x-amz-server-side-encryption-bucket-key-enabled`) that are specified in the `CreateSession` request. You don't
     *   need to explicitly specify these encryption settings values in Zonal endpoint API calls, and Amazon S3 will use the
     *   encryption settings values from the `CreateSession` request to protect new objects in the directory bucket.
     *
     *   > When you use the CLI or the Amazon Web Services SDKs, for `CreateSession`, the session token refreshes
     *   > automatically to avoid service interruptions when a session expires. The CLI or the Amazon Web Services SDKs use
     *   > the bucket's default encryption configuration for the `CreateSession` request. It's not supported to override the
     *   > encryption settings values in the `CreateSession` request. So in the Zonal endpoint API calls (except CopyObject
     *   > [^6] and UploadPartCopy [^7]), the encryption request headers must match the default encryption configuration of
     *   > the directory bucket.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingServerSideEncryption.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-specifying-kms-encryption.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^7]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

    /**
     * By default, Amazon S3 uses the STANDARD Storage Class to store newly created objects. The STANDARD storage class
     * provides high durability and high availability. Depending on performance needs, you can specify a different Storage
     * Class. For more information, see Storage Classes [^1] in the *Amazon S3 User Guide*.
     *
     * > - For directory buckets, only the S3 Express One Zone storage class is supported to store newly created objects.
     * > - Amazon S3 on Outposts only uses the OUTPOSTS Storage Class.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/storage-class-intro.html
     *
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata. For information about object
     * metadata, see Object Key and Metadata [^1] in the *Amazon S3 User Guide*.
     *
     * In the following example, the request header sets the redirect to an object (anotherPage.html) in the same bucket:
     *
     * `x-amz-website-redirect-location: /anotherPage.html`
     *
     * In the following example, the request header sets the object redirect to another website:
     *
     * `x-amz-website-redirect-location: http://www.example.com/`
     *
     * For more information about website hosting in Amazon S3, see Hosting Websites on Amazon S3 [^2] and How to Configure
     * Website Page Redirects [^3] in the *Amazon S3 User Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingMetadata.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/WebsiteHosting.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/dev/how-to-page-redirect.html
     *
     * @var string|null
     */
    private $websiteRedirectLocation;

    /**
     * Specifies the algorithm to use when encrypting the object (for example, `AES256`).
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseCustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side-encryption-customer-algorithm` header.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseCustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseCustomerKeyMd5;

    /**
     * Specifies the KMS key ID (Key ID, Key ARN, or Key Alias) to use for object encryption. If the KMS key doesn't exist
     * in the same account that's issuing the command, you must use the full Key ARN not the Key ID.
     *
     * **General purpose buckets** - If you specify `x-amz-server-side-encryption` with `aws:kms` or `aws:kms:dsse`, this
     * header specifies the ID (Key ID, Key ARN, or Key Alias) of the KMS key to use. If you specify
     * `x-amz-server-side-encryption:aws:kms` or `x-amz-server-side-encryption:aws:kms:dsse`, but do not provide
     * `x-amz-server-side-encryption-aws-kms-key-id`, Amazon S3 uses the Amazon Web Services managed key (`aws/s3`) to
     * protect the data.
     *
     * **Directory buckets** - If you specify `x-amz-server-side-encryption` with `aws:kms`, the `
     * x-amz-server-side-encryption-aws-kms-key-id` header is implicitly assigned the ID of the KMS symmetric encryption
     * customer managed key that's configured for your directory bucket's default encryption setting. If you want to specify
     * the ` x-amz-server-side-encryption-aws-kms-key-id` header explicitly, you can only specify it with the ID (Key ID or
     * Key ARN) of the KMS customer managed key that's configured for your directory bucket's default encryption setting.
     * Otherwise, you get an HTTP `400 Bad Request` error. Only use the key ID or key ARN. The key alias format of the KMS
     * key isn't supported. Your SSE-KMS configuration can only support 1 customer managed key [^1] per directory bucket for
     * the lifetime of the bucket. The Amazon Web Services managed key [^2] (`aws/s3`) isn't supported.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-cmk
     *
     * @var string|null
     */
    private $sseKmsKeyId;

    /**
     * Specifies the Amazon Web Services KMS Encryption Context as an additional encryption context to use for object
     * encryption. The value of this header is a Base64-encoded string of a UTF-8 encoded JSON, which contains the
     * encryption context as key-value pairs. This value is stored as object metadata and automatically gets passed on to
     * Amazon Web Services KMS for future `GetObject` operations on this object.
     *
     * **General purpose buckets** - This value must be explicitly added during `CopyObject` operations if you want an
     * additional encryption context for your object. For more information, see Encryption context [^1] in the *Amazon S3
     * User Guide*.
     *
     * **Directory buckets** - You can optionally provide an explicit encryption context value. The value must match the
     * default encryption context - the bucket Amazon Resource Name (ARN). An additional encryption context value is not
     * supported.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/UsingKMSEncryption.html#encryption-context
     *
     * @var string|null
     */
    private $sseKmsEncryptionContext;

    /**
     * Specifies whether Amazon S3 should use an S3 Bucket Key for object encryption with server-side encryption using Key
     * Management Service (KMS) keys (SSE-KMS).
     *
     * **General purpose buckets** - Setting this header to `true` causes Amazon S3 to use an S3 Bucket Key for object
     * encryption with SSE-KMS. Also, specifying this header with a PUT action doesn't affect bucket-level settings for S3
     * Bucket Key.
     *
     * **Directory buckets** - S3 Bucket Keys are always enabled for `GET` and `PUT` operations in a directory bucket and
     * can’t be disabled. S3 Bucket Keys aren't supported, when you copy SSE-KMS encrypted objects from general purpose
     * buckets to directory buckets, from directory buckets to general purpose buckets, or between directory buckets,
     * through CopyObject [^1], UploadPartCopy [^2], the Copy operation in Batch Operations [^3], or the import jobs [^4].
     * In this case, Amazon S3 makes a call to KMS every time a copy request is made for a KMS-encrypted object.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-objects-Batch-Ops
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/create-import-job
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * The tag-set for the object. The tag-set must be encoded as URL Query parameters. (For example, "Key1=Value1").
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $tagging;

    /**
     * The Object Lock mode that you want to apply to this object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockMode::*|null
     */
    private $objectLockMode;

    /**
     * The date and time when you want this object's Object Lock to expire. Must be formatted as a timestamp parameter.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $objectLockRetainUntilDate;

    /**
     * Specifies whether a legal hold will be applied to this object. For more information about S3 Object Lock, see Object
     * Lock [^1] in the *Amazon S3 User Guide*.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock.html
     *
     * @var ObjectLockLegalHoldStatus::*|null
     */
    private $objectLockLegalHoldStatus;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Body?: null|string|resource|(callable(int): string)|iterable<string>,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentLength?: null|int,
     *   ContentMD5?: null|string,
     *   ContentType?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
     *   WriteOffsetBytes?: null|int,
     *   Metadata?: null|array<string, string>,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->acl = $input['ACL'] ?? null;
        $this->body = $input['Body'] ?? null;
        $this->bucket = $input['Bucket'] ?? null;
        $this->cacheControl = $input['CacheControl'] ?? null;
        $this->contentDisposition = $input['ContentDisposition'] ?? null;
        $this->contentEncoding = $input['ContentEncoding'] ?? null;
        $this->contentLanguage = $input['ContentLanguage'] ?? null;
        $this->contentLength = $input['ContentLength'] ?? null;
        $this->contentMd5 = $input['ContentMD5'] ?? null;
        $this->contentType = $input['ContentType'] ?? null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->checksumCrc32 = $input['ChecksumCRC32'] ?? null;
        $this->checksumCrc32C = $input['ChecksumCRC32C'] ?? null;
        $this->checksumSha1 = $input['ChecksumSHA1'] ?? null;
        $this->checksumSha256 = $input['ChecksumSHA256'] ?? null;
        $this->expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->ifMatch = $input['IfMatch'] ?? null;
        $this->ifNoneMatch = $input['IfNoneMatch'] ?? null;
        $this->grantFullControl = $input['GrantFullControl'] ?? null;
        $this->grantRead = $input['GrantRead'] ?? null;
        $this->grantReadAcp = $input['GrantReadACP'] ?? null;
        $this->grantWriteAcp = $input['GrantWriteACP'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->writeOffsetBytes = $input['WriteOffsetBytes'] ?? null;
        $this->metadata = $input['Metadata'] ?? null;
        $this->serverSideEncryption = $input['ServerSideEncryption'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->websiteRedirectLocation = $input['WebsiteRedirectLocation'] ?? null;
        $this->sseCustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->sseCustomerKey = $input['SSECustomerKey'] ?? null;
        $this->sseCustomerKeyMd5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->sseKmsKeyId = $input['SSEKMSKeyId'] ?? null;
        $this->sseKmsEncryptionContext = $input['SSEKMSEncryptionContext'] ?? null;
        $this->bucketKeyEnabled = $input['BucketKeyEnabled'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->tagging = $input['Tagging'] ?? null;
        $this->objectLockMode = $input['ObjectLockMode'] ?? null;
        $this->objectLockRetainUntilDate = !isset($input['ObjectLockRetainUntilDate']) ? null : ($input['ObjectLockRetainUntilDate'] instanceof \DateTimeImmutable ? $input['ObjectLockRetainUntilDate'] : new \DateTimeImmutable($input['ObjectLockRetainUntilDate']));
        $this->objectLockLegalHoldStatus = $input['ObjectLockLegalHoldStatus'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Body?: null|string|resource|(callable(int): string)|iterable<string>,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentLength?: null|int,
     *   ContentMD5?: null|string,
     *   ContentType?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumCRC32?: null|string,
     *   ChecksumCRC32C?: null|string,
     *   ChecksumSHA1?: null|string,
     *   ChecksumSHA256?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   IfMatch?: null|string,
     *   IfNoneMatch?: null|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
     *   WriteOffsetBytes?: null|int,
     *   Metadata?: null|array<string, string>,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|PutObjectRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectCannedACL::*|null
     */
    public function getAcl(): ?string
    {
        return $this->acl;
    }

    /**
     * @return string|resource|(callable(int): string)|iterable<string>|null
     */
    public function getBody()
    {
        return $this->body;
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getBucketKeyEnabled(): ?bool
    {
        return $this->bucketKeyEnabled;
    }

    public function getCacheControl(): ?string
    {
        return $this->cacheControl;
    }

    /**
     * @return ChecksumAlgorithm::*|null
     */
    public function getChecksumAlgorithm(): ?string
    {
        return $this->checksumAlgorithm;
    }

    public function getChecksumCrc32(): ?string
    {
        return $this->checksumCrc32;
    }

    public function getChecksumCrc32C(): ?string
    {
        return $this->checksumCrc32C;
    }

    public function getChecksumSha1(): ?string
    {
        return $this->checksumSha1;
    }

    public function getChecksumSha256(): ?string
    {
        return $this->checksumSha256;
    }

    public function getContentDisposition(): ?string
    {
        return $this->contentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        return $this->contentLanguage;
    }

    public function getContentLength(): ?int
    {
        return $this->contentLength;
    }

    public function getContentMd5(): ?string
    {
        return $this->contentMd5;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->grantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->grantRead;
    }

    public function getGrantReadAcp(): ?string
    {
        return $this->grantReadAcp;
    }

    public function getGrantWriteAcp(): ?string
    {
        return $this->grantWriteAcp;
    }

    public function getIfMatch(): ?string
    {
        return $this->ifMatch;
    }

    public function getIfNoneMatch(): ?string
    {
        return $this->ifNoneMatch;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }

    /**
     * @return ObjectLockLegalHoldStatus::*|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        return $this->objectLockLegalHoldStatus;
    }

    /**
     * @return ObjectLockMode::*|null
     */
    public function getObjectLockMode(): ?string
    {
        return $this->objectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        return $this->objectLockRetainUntilDate;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        return $this->serverSideEncryption;
    }

    public function getSseCustomerAlgorithm(): ?string
    {
        return $this->sseCustomerAlgorithm;
    }

    public function getSseCustomerKey(): ?string
    {
        return $this->sseCustomerKey;
    }

    public function getSseCustomerKeyMd5(): ?string
    {
        return $this->sseCustomerKeyMd5;
    }

    public function getSseKmsEncryptionContext(): ?string
    {
        return $this->sseKmsEncryptionContext;
    }

    public function getSseKmsKeyId(): ?string
    {
        return $this->sseKmsKeyId;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    public function getTagging(): ?string
    {
        return $this->tagging;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        return $this->websiteRedirectLocation;
    }

    public function getWriteOffsetBytes(): ?int
    {
        return $this->writeOffsetBytes;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [];
        if (null !== $this->acl) {
            if (!ObjectCannedACL::exists($this->acl)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->acl));
            }
            $headers['x-amz-acl'] = $this->acl;
        }
        if (null !== $this->cacheControl) {
            $headers['Cache-Control'] = $this->cacheControl;
        }
        if (null !== $this->contentDisposition) {
            $headers['Content-Disposition'] = $this->contentDisposition;
        }
        if (null !== $this->contentEncoding) {
            $headers['Content-Encoding'] = $this->contentEncoding;
        }
        if (null !== $this->contentLanguage) {
            $headers['Content-Language'] = $this->contentLanguage;
        }
        if (null !== $this->contentLength) {
            $headers['Content-Length'] = (string) $this->contentLength;
        }
        if (null !== $this->contentMd5) {
            $headers['Content-MD5'] = $this->contentMd5;
        }
        if (null !== $this->contentType) {
            $headers['Content-Type'] = $this->contentType;
        }
        if (null !== $this->checksumAlgorithm) {
            if (!ChecksumAlgorithm::exists($this->checksumAlgorithm)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ChecksumAlgorithm" for "%s". The value "%s" is not a valid "ChecksumAlgorithm".', __CLASS__, $this->checksumAlgorithm));
            }
            $headers['x-amz-sdk-checksum-algorithm'] = $this->checksumAlgorithm;
        }
        if (null !== $this->checksumCrc32) {
            $headers['x-amz-checksum-crc32'] = $this->checksumCrc32;
        }
        if (null !== $this->checksumCrc32C) {
            $headers['x-amz-checksum-crc32c'] = $this->checksumCrc32C;
        }
        if (null !== $this->checksumSha1) {
            $headers['x-amz-checksum-sha1'] = $this->checksumSha1;
        }
        if (null !== $this->checksumSha256) {
            $headers['x-amz-checksum-sha256'] = $this->checksumSha256;
        }
        if (null !== $this->expires) {
            $headers['Expires'] = $this->expires->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->ifMatch) {
            $headers['If-Match'] = $this->ifMatch;
        }
        if (null !== $this->ifNoneMatch) {
            $headers['If-None-Match'] = $this->ifNoneMatch;
        }
        if (null !== $this->grantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->grantFullControl;
        }
        if (null !== $this->grantRead) {
            $headers['x-amz-grant-read'] = $this->grantRead;
        }
        if (null !== $this->grantReadAcp) {
            $headers['x-amz-grant-read-acp'] = $this->grantReadAcp;
        }
        if (null !== $this->grantWriteAcp) {
            $headers['x-amz-grant-write-acp'] = $this->grantWriteAcp;
        }
        if (null !== $this->writeOffsetBytes) {
            $headers['x-amz-write-offset-bytes'] = (string) $this->writeOffsetBytes;
        }
        if (null !== $this->serverSideEncryption) {
            if (!ServerSideEncryption::exists($this->serverSideEncryption)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ServerSideEncryption" for "%s". The value "%s" is not a valid "ServerSideEncryption".', __CLASS__, $this->serverSideEncryption));
            }
            $headers['x-amz-server-side-encryption'] = $this->serverSideEncryption;
        }
        if (null !== $this->storageClass) {
            if (!StorageClass::exists($this->storageClass)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "StorageClass" for "%s". The value "%s" is not a valid "StorageClass".', __CLASS__, $this->storageClass));
            }
            $headers['x-amz-storage-class'] = $this->storageClass;
        }
        if (null !== $this->websiteRedirectLocation) {
            $headers['x-amz-website-redirect-location'] = $this->websiteRedirectLocation;
        }
        if (null !== $this->sseCustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->sseCustomerAlgorithm;
        }
        if (null !== $this->sseCustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->sseCustomerKey;
        }
        if (null !== $this->sseCustomerKeyMd5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->sseCustomerKeyMd5;
        }
        if (null !== $this->sseKmsKeyId) {
            $headers['x-amz-server-side-encryption-aws-kms-key-id'] = $this->sseKmsKeyId;
        }
        if (null !== $this->sseKmsEncryptionContext) {
            $headers['x-amz-server-side-encryption-context'] = $this->sseKmsEncryptionContext;
        }
        if (null !== $this->bucketKeyEnabled) {
            $headers['x-amz-server-side-encryption-bucket-key-enabled'] = $this->bucketKeyEnabled ? 'true' : 'false';
        }
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->tagging) {
            $headers['x-amz-tagging'] = $this->tagging;
        }
        if (null !== $this->objectLockMode) {
            if (!ObjectLockMode::exists($this->objectLockMode)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ObjectLockMode" for "%s". The value "%s" is not a valid "ObjectLockMode".', __CLASS__, $this->objectLockMode));
            }
            $headers['x-amz-object-lock-mode'] = $this->objectLockMode;
        }
        if (null !== $this->objectLockRetainUntilDate) {
            $headers['x-amz-object-lock-retain-until-date'] = $this->objectLockRetainUntilDate->format(\DateTimeInterface::ATOM);
        }
        if (null !== $this->objectLockLegalHoldStatus) {
            if (!ObjectLockLegalHoldStatus::exists($this->objectLockLegalHoldStatus)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ObjectLockLegalHoldStatus" for "%s". The value "%s" is not a valid "ObjectLockLegalHoldStatus".', __CLASS__, $this->objectLockLegalHoldStatus));
            }
            $headers['x-amz-object-lock-legal-hold'] = $this->objectLockLegalHoldStatus;
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }
        if (null !== $this->metadata) {
            foreach ($this->metadata as $key => $value) {
                $headers["x-amz-meta-$key"] = $value;
            }
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(\sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key']));

        // Prepare Body
        $body = $this->body ?? '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ObjectCannedACL::*|null $value
     */
    public function setAcl(?string $value): self
    {
        $this->acl = $value;

        return $this;
    }

    /**
     * @param string|resource|(callable(int): string)|iterable<string>|null $value
     */
    public function setBody($value): self
    {
        $this->body = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setBucketKeyEnabled(?bool $value): self
    {
        $this->bucketKeyEnabled = $value;

        return $this;
    }

    public function setCacheControl(?string $value): self
    {
        $this->cacheControl = $value;

        return $this;
    }

    /**
     * @param ChecksumAlgorithm::*|null $value
     */
    public function setChecksumAlgorithm(?string $value): self
    {
        $this->checksumAlgorithm = $value;

        return $this;
    }

    public function setChecksumCrc32(?string $value): self
    {
        $this->checksumCrc32 = $value;

        return $this;
    }

    public function setChecksumCrc32C(?string $value): self
    {
        $this->checksumCrc32C = $value;

        return $this;
    }

    public function setChecksumSha1(?string $value): self
    {
        $this->checksumSha1 = $value;

        return $this;
    }

    public function setChecksumSha256(?string $value): self
    {
        $this->checksumSha256 = $value;

        return $this;
    }

    public function setContentDisposition(?string $value): self
    {
        $this->contentDisposition = $value;

        return $this;
    }

    public function setContentEncoding(?string $value): self
    {
        $this->contentEncoding = $value;

        return $this;
    }

    public function setContentLanguage(?string $value): self
    {
        $this->contentLanguage = $value;

        return $this;
    }

    public function setContentLength(?int $value): self
    {
        $this->contentLength = $value;

        return $this;
    }

    public function setContentMd5(?string $value): self
    {
        $this->contentMd5 = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->contentType = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setExpires(?\DateTimeImmutable $value): self
    {
        $this->expires = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->grantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->grantRead = $value;

        return $this;
    }

    public function setGrantReadAcp(?string $value): self
    {
        $this->grantReadAcp = $value;

        return $this;
    }

    public function setGrantWriteAcp(?string $value): self
    {
        $this->grantWriteAcp = $value;

        return $this;
    }

    public function setIfMatch(?string $value): self
    {
        $this->ifMatch = $value;

        return $this;
    }

    public function setIfNoneMatch(?string $value): self
    {
        $this->ifNoneMatch = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setMetadata(array $value): self
    {
        $this->metadata = $value;

        return $this;
    }

    /**
     * @param ObjectLockLegalHoldStatus::*|null $value
     */
    public function setObjectLockLegalHoldStatus(?string $value): self
    {
        $this->objectLockLegalHoldStatus = $value;

        return $this;
    }

    /**
     * @param ObjectLockMode::*|null $value
     */
    public function setObjectLockMode(?string $value): self
    {
        $this->objectLockMode = $value;

        return $this;
    }

    public function setObjectLockRetainUntilDate(?\DateTimeImmutable $value): self
    {
        $this->objectLockRetainUntilDate = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->requestPayer = $value;

        return $this;
    }

    /**
     * @param ServerSideEncryption::*|null $value
     */
    public function setServerSideEncryption(?string $value): self
    {
        $this->serverSideEncryption = $value;

        return $this;
    }

    public function setSseCustomerAlgorithm(?string $value): self
    {
        $this->sseCustomerAlgorithm = $value;

        return $this;
    }

    public function setSseCustomerKey(?string $value): self
    {
        $this->sseCustomerKey = $value;

        return $this;
    }

    public function setSseCustomerKeyMd5(?string $value): self
    {
        $this->sseCustomerKeyMd5 = $value;

        return $this;
    }

    public function setSseKmsEncryptionContext(?string $value): self
    {
        $this->sseKmsEncryptionContext = $value;

        return $this;
    }

    public function setSseKmsKeyId(?string $value): self
    {
        $this->sseKmsKeyId = $value;

        return $this;
    }

    /**
     * @param StorageClass::*|null $value
     */
    public function setStorageClass(?string $value): self
    {
        $this->storageClass = $value;

        return $this;
    }

    public function setTagging(?string $value): self
    {
        $this->tagging = $value;

        return $this;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->websiteRedirectLocation = $value;

        return $this;
    }

    public function setWriteOffsetBytes(?int $value): self
    {
        $this->writeOffsetBytes = $value;

        return $this;
    }
}
