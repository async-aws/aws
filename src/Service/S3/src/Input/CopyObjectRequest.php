<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\MetadataDirective;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;
use AsyncAws\S3\Enum\TaggingDirective;

final class CopyObjectRequest extends Input
{
    /**
     * The canned access control list (ACL) to apply to the object.
     *
     * When you copy an object, the ACL metadata is not preserved and is set to `private` by default. Only the owner has
     * full access control. To override the default ACL setting, specify a new ACL when you generate a copy request. For
     * more information, see Using ACLs [^1].
     *
     * If the destination bucket that you're copying objects to uses the bucket owner enforced setting for S3 Object
     * Ownership, ACLs are disabled and no longer affect permissions. Buckets that use this setting only accept `PUT`
     * requests that don't specify an ACL or `PUT` requests that specify bucket owner full control ACLs, such as the
     * `bucket-owner-full-control` canned ACL or an equivalent form of this ACL expressed in the XML format. For more
     * information, see Controlling ownership of objects and disabling ACLs [^2] in the *Amazon S3 User Guide*.
     *
     * > - If your destination bucket uses the bucket owner enforced setting for Object Ownership, all objects written to
     * >   the bucket by any account will be owned by the bucket owner.
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/S3_ACLs_UsingACLs.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/about-object-ownership.html
     *
     * @var ObjectCannedACL::*|null
     */
    private $acl;

    /**
     * The name of the destination bucket.
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
     * Specifies the caching behavior along the request/reply chain.
     *
     * @var string|null
     */
    private $cacheControl;

    /**
     * Indicates the algorithm that you want Amazon S3 to use to create the checksum for the object. For more information,
     * see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * When you copy an object, if the source object has a checksum, that checksum value will be copied to the new object by
     * default. If the `CopyObject` request does not include this `x-amz-checksum-algorithm` header, the checksum algorithm
     * will be copied from the source object to the destination object (if it's present on the source object). You can
     * optionally specify a different checksum algorithm to use with the `x-amz-checksum-algorithm` header. Unrecognized or
     * unsupported values will respond with the HTTP status code `400 Bad Request`.
     *
     * > For directory buckets, when you use Amazon Web Services SDKs, `CRC32` is the default checksum algorithm that's used
     * > for performance.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * Specifies presentational information for the object. Indicates whether an object should be displayed in a web browser
     * or downloaded as a file. It allows specifying the desired filename for the downloaded file.
     *
     * @var string|null
     */
    private $contentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field.
     *
     * > For directory buckets, only the `aws-chunked` value is supported in this header field.
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
     * A standard MIME type that describes the format of the object data.
     *
     * @var string|null
     */
    private $contentType;

    /**
     * Specifies the source object for the copy operation. The source object can be up to 5 GB. If the source object is an
     * object that was uploaded by using a multipart upload, the object copy will be a single part object after the source
     * object is copied to the destination bucket.
     *
     * You specify the value of the copy source in one of two formats, depending on whether you want to access the source
     * object through an access point [^1]:
     *
     * - For objects not accessed through an access point, specify the name of the source bucket and the key of the source
     *   object, separated by a slash (/). For example, to copy the object `reports/january.pdf` from the general purpose
     *   bucket `awsexamplebucket`, use `awsexamplebucket/reports/january.pdf`. The value must be URL-encoded. To copy the
     *   object `reports/january.pdf` from the directory bucket `awsexamplebucket--use1-az5--x-s3`, use
     *   `awsexamplebucket--use1-az5--x-s3/reports/january.pdf`. The value must be URL-encoded.
     * - For objects accessed through access points, specify the Amazon Resource Name (ARN) of the object as accessed
     *   through the access point, in the format
     *   `arn:aws:s3:<Region>:<account-id>:accesspoint/<access-point-name>/object/<key>`. For
     *   example, to copy the object `reports/january.pdf` through access point `my-access-point` owned by account
     *   `123456789012` in Region `us-west-2`, use the URL encoding of
     *   `arn:aws:s3:us-west-2:123456789012:accesspoint/my-access-point/object/reports/january.pdf`. The value must be URL
     *   encoded.
     *
     *   > - Amazon S3 supports copy operations using Access points only when the source and destination buckets are in the
     *   >   same Amazon Web Services Region.
     *   > - Access points are not supported by directory buckets.
     *   >
     *
     *   Alternatively, for objects accessed through Amazon S3 on Outposts, specify the ARN of the object as accessed in the
     *   format `arn:aws:s3-outposts:<Region>:<account-id>:outpost/<outpost-id>/object/<key>`. For
     *   example, to copy the object `reports/january.pdf` through outpost `my-outpost` owned by account `123456789012` in
     *   Region `us-west-2`, use the URL encoding of
     *   `arn:aws:s3-outposts:us-west-2:123456789012:outpost/my-outpost/object/reports/january.pdf`. The value must be
     *   URL-encoded.
     *
     * If your source bucket versioning is enabled, the `x-amz-copy-source` header by default identifies the current version
     * of an object to copy. If the current version is a delete marker, Amazon S3 behaves as if the object was deleted. To
     * copy a different version, use the `versionId` query parameter. Specifically, append `?versionId=<version-id>`
     * to the value (for example, `awsexamplebucket/reports/january.pdf?versionId=QUpfdndhfd8438MNFDN93jdnJFkdmqnh893`). If
     * you don't specify a version ID, Amazon S3 copies the latest version of the source object.
     *
     * If you enable versioning on the destination bucket, Amazon S3 generates a unique version ID for the copied object.
     * This version ID is different from the version ID of the source object. Amazon S3 returns the version ID of the copied
     * object in the `x-amz-version-id` response header in the response.
     *
     * If you do not enable versioning or suspend it on the destination bucket, the version ID that Amazon S3 generates in
     * the `x-amz-version-id` response header is always null.
     *
     * > **Directory buckets** - S3 Versioning isn't enabled and supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/access-points.html
     *
     * @required
     *
     * @var string|null
     */
    private $copySource;

    /**
     * Copies the object if its entity tag (ETag) matches the specified tag.
     *
     * If both the `x-amz-copy-source-if-match` and `x-amz-copy-source-if-unmodified-since` headers are present in the
     * request and evaluate as follows, Amazon S3 returns `200 OK` and copies the data:
     *
     * - `x-amz-copy-source-if-match` condition evaluates to true
     * - `x-amz-copy-source-if-unmodified-since` condition evaluates to false
     *
     * @var string|null
     */
    private $copySourceIfMatch;

    /**
     * Copies the object if it has been modified since the specified time.
     *
     * If both the `x-amz-copy-source-if-none-match` and `x-amz-copy-source-if-modified-since` headers are present in the
     * request and evaluate as follows, Amazon S3 returns the `412 Precondition Failed` response code:
     *
     * - `x-amz-copy-source-if-none-match` condition evaluates to false
     * - `x-amz-copy-source-if-modified-since` condition evaluates to true
     *
     * @var \DateTimeImmutable|null
     */
    private $copySourceIfModifiedSince;

    /**
     * Copies the object if its entity tag (ETag) is different than the specified ETag.
     *
     * If both the `x-amz-copy-source-if-none-match` and `x-amz-copy-source-if-modified-since` headers are present in the
     * request and evaluate as follows, Amazon S3 returns the `412 Precondition Failed` response code:
     *
     * - `x-amz-copy-source-if-none-match` condition evaluates to false
     * - `x-amz-copy-source-if-modified-since` condition evaluates to true
     *
     * @var string|null
     */
    private $copySourceIfNoneMatch;

    /**
     * Copies the object if it hasn't been modified since the specified time.
     *
     * If both the `x-amz-copy-source-if-match` and `x-amz-copy-source-if-unmodified-since` headers are present in the
     * request and evaluate as follows, Amazon S3 returns `200 OK` and copies the data:
     *
     * - `x-amz-copy-source-if-match` condition evaluates to true
     * - `x-amz-copy-source-if-unmodified-since` condition evaluates to false
     *
     * @var \DateTimeImmutable|null
     */
    private $copySourceIfUnmodifiedSince;

    /**
     * The date and time at which the object is no longer cacheable.
     *
     * @var \DateTimeImmutable|null
     */
    private $expires;

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
     * The key of the destination object.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>|null
     */
    private $metadata;

    /**
     * Specifies whether the metadata is copied from the source object or replaced with metadata that's provided in the
     * request. When copying an object, you can preserve all metadata (the default) or specify new metadata. If this header
     * isn’t specified, `COPY` is the default behavior.
     *
     * **General purpose bucket** - For general purpose buckets, when you grant permissions, you can use the
     * `s3:x-amz-metadata-directive` condition key to enforce certain metadata behavior when objects are uploaded. For more
     * information, see Amazon S3 condition key examples [^1] in the *Amazon S3 User Guide*.
     *
     * > `x-amz-website-redirect-location` is unique to each object and is not copied when using the
     * > `x-amz-metadata-directive` header. To copy the value, you must specify `x-amz-website-redirect-location` in the
     * > request header.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/amazon-s3-policy-keys.html
     *
     * @var MetadataDirective::*|null
     */
    private $metadataDirective;

    /**
     * Specifies whether the object tag-set is copied from the source object or replaced with the tag-set that's provided in
     * the request.
     *
     * The default value is `COPY`.
     *
     * > **Directory buckets** - For directory buckets in a `CopyObject` operation, only the empty tag-set is supported. Any
     * > requests that attempt to write non-empty tags into directory buckets will receive a `501 Not Implemented` status
     * > code. When the destination bucket is a directory bucket, you will receive a `501 Not Implemented` response in any
     * > of the following situations:
     * >
     * > - When you attempt to `COPY` the tag-set from an S3 source object that has non-empty tags.
     * > - When you attempt to `REPLACE` the tag-set of a source object and set a non-empty value to `x-amz-tagging`.
     * > - When you don't set the `x-amz-tagging-directive` header and the source object has non-empty tags. This is because
     * >   the default value of `x-amz-tagging-directive` is `COPY`.
     * >
     * > Because only the empty tag-set is supported for directory buckets in a `CopyObject` operation, the following
     * > situations are allowed:
     * >
     * > - When you attempt to `COPY` the tag-set from a directory bucket source object that has no tags to a general
     * >   purpose bucket. It copies an empty tag-set to the destination object.
     * > - When you attempt to `REPLACE` the tag-set of a directory bucket source object and set the `x-amz-tagging` value
     * >   of the directory bucket destination object to empty.
     * > - When you attempt to `REPLACE` the tag-set of a general purpose bucket source object that has non-empty tags and
     * >   set the `x-amz-tagging` value of the directory bucket destination object to empty.
     * > - When you attempt to `REPLACE` the tag-set of a directory bucket source object and don't set the `x-amz-tagging`
     * >   value of the directory bucket destination object. This is because the default value of `x-amz-tagging` is the
     * >   empty value.
     * >
     *
     * @var TaggingDirective::*|null
     */
    private $taggingDirective;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3. Unrecognized or unsupported values
     * won’t write a destination object and will receive a `400 Bad Request` response.
     *
     * Amazon S3 automatically encrypts all new objects that are copied to an S3 bucket. When copying an object, if you
     * don't specify encryption information in your copy request, the encryption setting of the target object is set to the
     * default encryption configuration of the destination bucket. By default, all buckets have a base level of encryption
     * configuration that uses server-side encryption with Amazon S3 managed keys (SSE-S3). If the destination bucket has a
     * different default encryption configuration, Amazon S3 uses the corresponding encryption key to encrypt the target
     * object copy.
     *
     * With server-side encryption, Amazon S3 encrypts your data as it writes your data to disks in its data centers and
     * decrypts the data when you access it. For more information about server-side encryption, see Using Server-Side
     * Encryption [^1] in the *Amazon S3 User Guide*.
     *
     * **General purpose buckets **
     *
     * - For general purpose buckets, there are the following supported options for server-side encryption: server-side
     *   encryption with Key Management Service (KMS) keys (SSE-KMS), dual-layer server-side encryption with Amazon Web
     *   Services KMS keys (DSSE-KMS), and server-side encryption with customer-provided encryption keys (SSE-C). Amazon S3
     *   uses the corresponding KMS key, or a customer-provided key to encrypt the target object copy.
     * - When you perform a `CopyObject` operation, if you want to use a different type of encryption setting for the target
     *   object, you can specify appropriate encryption-related headers to encrypt the target object with an Amazon S3
     *   managed key, a KMS key, or a customer-provided key. If the encryption setting in your request is different from the
     *   default encryption configuration of the destination bucket, the encryption setting in your request takes
     *   precedence.
     *
     * **Directory buckets **
     *
     * - For directory buckets, there are only two supported options for server-side encryption: server-side encryption with
     *   Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys (SSE-KMS) (`aws:kms`). We
     *   recommend that the bucket's default encryption uses the desired encryption configuration and you don't override the
     *   bucket default encryption in your `CreateSession` requests or `PUT` object requests. Then, new objects are
     *   automatically encrypted with the desired encryption settings. For more information, see Protecting data with
     *   server-side encryption [^2] in the *Amazon S3 User Guide*. For more information about the encryption overriding
     *   behaviors in directory buckets, see Specifying server-side encryption with KMS for new object uploads [^3].
     * - To encrypt new object copies to a directory bucket with SSE-KMS, we recommend you specify SSE-KMS as the directory
     *   bucket's default encryption configuration with a KMS key (specifically, a customer managed key [^4]). The Amazon
     *   Web Services managed key [^5] (`aws/s3`) isn't supported. Your SSE-KMS configuration can only support 1 customer
     *   managed key [^6] per directory bucket for the lifetime of the bucket. After you specify a customer managed key for
     *   SSE-KMS, you can't override the customer managed key for the bucket's SSE-KMS configuration. Then, when you perform
     *   a `CopyObject` operation and want to specify server-side encryption settings for new object copies with SSE-KMS in
     *   the encryption-related request headers, you must ensure the encryption key is the same customer managed key that
     *   you specified for the directory bucket's default encryption configuration.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/serv-side-encryption.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-specifying-kms-encryption.html
     * [^4]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     * [^5]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-cmk
     * [^6]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

    /**
     * If the `x-amz-storage-class` header is not used, the copied object will be stored in the `STANDARD` Storage Class by
     * default. The `STANDARD` storage class provides high durability and high availability. Depending on performance needs,
     * you can specify a different Storage Class.
     *
     * > - **Directory buckets ** - For directory buckets, only the S3 Express One Zone storage class is supported to store
     * >   newly created objects. Unsupported storage class values won't write a destination object and will respond with
     * >   the HTTP status code `400 Bad Request`.
     * > - **Amazon S3 on Outposts ** - S3 on Outposts only uses the `OUTPOSTS` Storage Class.
     * >
     *
     * You can use the `CopyObject` action to change the storage class of an object that is already stored in Amazon S3 by
     * using the `x-amz-storage-class` header. For more information, see Storage Classes [^1] in the *Amazon S3 User Guide*.
     *
     * Before using an object as a source object for the copy operation, you must restore a copy of it if it meets any of
     * the following conditions:
     *
     * - The storage class of the source object is `GLACIER` or `DEEP_ARCHIVE`.
     * - The storage class of the source object is `INTELLIGENT_TIERING` and it's S3 Intelligent-Tiering access tier [^2] is
     *   `Archive Access` or `Deep Archive Access`.
     *
     * For more information, see RestoreObject [^3] and Copying Objects [^4] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/storage-class-intro.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/intelligent-tiering-overview.html#intel-tiering-tier-definition
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_RestoreObject.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/dev/CopyingObjectsExamples.html
     *
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * If the destination bucket is configured as a website, redirects requests for this object copy to another object in
     * the same bucket or to an external URL. Amazon S3 stores the value of this header in the object metadata. This value
     * is unique to each object and is not copied when using the `x-amz-metadata-directive` header. Instead, you may opt to
     * provide this header in combination with the `x-amz-metadata-directive` header.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $websiteRedirectLocation;

    /**
     * Specifies the algorithm to use when encrypting the object (for example, `AES256`).
     *
     * When you perform a `CopyObject` operation, if you want to use a different type of encryption setting for the target
     * object, you can specify appropriate encryption-related headers to encrypt the target object with an Amazon S3 managed
     * key, a KMS key, or a customer-provided key. If the encryption setting in your request is different from the default
     * encryption configuration of the destination bucket, the encryption setting in your request takes precedence.
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded. Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side-encryption-customer-algorithm` header.
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerKeyMd5;

    /**
     * Specifies the KMS key ID (Key ID, Key ARN, or Key Alias) to use for object encryption. All GET and PUT requests for
     * an object protected by KMS will fail if they're not made via SSL or using SigV4. For information about configuring
     * any of the officially supported Amazon Web Services SDKs and Amazon Web Services CLI, see Specifying the Signature
     * Version in Request Authentication [^1] in the *Amazon S3 User Guide*.
     *
     * **Directory buckets** - If you specify `x-amz-server-side-encryption` with `aws:kms`, the `
     * x-amz-server-side-encryption-aws-kms-key-id` header is implicitly assigned the ID of the KMS symmetric encryption
     * customer managed key that's configured for your directory bucket's default encryption setting. If you want to specify
     * the ` x-amz-server-side-encryption-aws-kms-key-id` header explicitly, you can only specify it with the ID (Key ID or
     * Key ARN) of the KMS customer managed key that's configured for your directory bucket's default encryption setting.
     * Otherwise, you get an HTTP `400 Bad Request` error. Only use the key ID or key ARN. The key alias format of the KMS
     * key isn't supported. Your SSE-KMS configuration can only support 1 customer managed key [^2] per directory bucket for
     * the lifetime of the bucket. The Amazon Web Services managed key [^3] (`aws/s3`) isn't supported.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingAWSSDK.html#specify-signature-version
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-cmk
     *
     * @var string|null
     */
    private $sseKmsKeyId;

    /**
     * Specifies the Amazon Web Services KMS Encryption Context as an additional encryption context to use for the
     * destination object encryption. The value of this header is a base64-encoded UTF-8 string holding JSON with the
     * encryption context key-value pairs.
     *
     * **General purpose buckets** - This value must be explicitly added to specify encryption context for `CopyObject`
     * requests if you want an additional encryption context for your destination object. The additional encryption context
     * of the source object won't be copied to the destination object. For more information, see Encryption context [^1] in
     * the *Amazon S3 User Guide*.
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
     * Management Service (KMS) keys (SSE-KMS). If a target object uses SSE-KMS, you can enable an S3 Bucket Key for the
     * object.
     *
     * Setting this header to `true` causes Amazon S3 to use an S3 Bucket Key for object encryption with SSE-KMS. Specifying
     * this header with a COPY action doesn’t affect bucket-level settings for S3 Bucket Key.
     *
     * For more information, see Amazon S3 Bucket Keys [^1] in the *Amazon S3 User Guide*.
     *
     * > **Directory buckets** - S3 Bucket Keys aren't supported, when you copy SSE-KMS encrypted objects from general
     * > purpose buckets to directory buckets, from directory buckets to general purpose buckets, or between directory
     * > buckets, through CopyObject [^2]. In this case, Amazon S3 makes a call to KMS every time a copy request is made for
     * > a KMS-encrypted object.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/bucket-key.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * Specifies the algorithm to use when decrypting the source object (for example, `AES256`).
     *
     * If the source object for the copy is stored in Amazon S3 using SSE-C, you must provide the necessary encryption
     * information in your request so that Amazon S3 can decrypt the object for copying.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use to decrypt the source object. The encryption key
     * provided in this header must be the same one that was used when the source object was created.
     *
     * If the source object for the copy is stored in Amazon S3 using SSE-C, you must provide the necessary encryption
     * information in your request so that Amazon S3 can decrypt the object for copying.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * If the source object for the copy is stored in Amazon S3 using SSE-C, you must provide the necessary encryption
     * information in your request so that Amazon S3 can decrypt the object for copying.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerKeyMd5;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * The tag-set for the object copy in the destination bucket. This value must be used in conjunction with the
     * `x-amz-tagging-directive` if you choose `REPLACE` for the `x-amz-tagging-directive`. If you choose `COPY` for the
     * `x-amz-tagging-directive`, you don't need to set the `x-amz-tagging` header, because the tag-set will be copied from
     * the source object directly. The tag-set must be encoded as URL Query parameters.
     *
     * The default value is the empty value.
     *
     * > **Directory buckets** - For directory buckets in a `CopyObject` operation, only the empty tag-set is supported. Any
     * > requests that attempt to write non-empty tags into directory buckets will receive a `501 Not Implemented` status
     * > code. When the destination bucket is a directory bucket, you will receive a `501 Not Implemented` response in any
     * > of the following situations:
     * >
     * > - When you attempt to `COPY` the tag-set from an S3 source object that has non-empty tags.
     * > - When you attempt to `REPLACE` the tag-set of a source object and set a non-empty value to `x-amz-tagging`.
     * > - When you don't set the `x-amz-tagging-directive` header and the source object has non-empty tags. This is because
     * >   the default value of `x-amz-tagging-directive` is `COPY`.
     * >
     * > Because only the empty tag-set is supported for directory buckets in a `CopyObject` operation, the following
     * > situations are allowed:
     * >
     * > - When you attempt to `COPY` the tag-set from a directory bucket source object that has no tags to a general
     * >   purpose bucket. It copies an empty tag-set to the destination object.
     * > - When you attempt to `REPLACE` the tag-set of a directory bucket source object and set the `x-amz-tagging` value
     * >   of the directory bucket destination object to empty.
     * > - When you attempt to `REPLACE` the tag-set of a general purpose bucket source object that has non-empty tags and
     * >   set the `x-amz-tagging` value of the directory bucket destination object to empty.
     * > - When you attempt to `REPLACE` the tag-set of a directory bucket source object and don't set the `x-amz-tagging`
     * >   value of the directory bucket destination object. This is because the default value of `x-amz-tagging` is the
     * >   empty value.
     * >
     *
     * @var string|null
     */
    private $tagging;

    /**
     * The Object Lock mode that you want to apply to the object copy.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockMode::*|null
     */
    private $objectLockMode;

    /**
     * The date and time when you want the Object Lock of the object copy to expire.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $objectLockRetainUntilDate;

    /**
     * Specifies whether you want to apply a legal hold to the object copy.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockLegalHoldStatus::*|null
     */
    private $objectLockLegalHoldStatus;

    /**
     * The account ID of the expected destination bucket owner. If the account ID that you provide does not match the actual
     * owner of the destination bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * The account ID of the expected source bucket owner. If the account ID that you provide does not match the actual
     * owner of the source bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedSourceBucketOwner;

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   CopySource?: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
     *   Metadata?: null|array<string, string>,
     *   MetadataDirective?: null|MetadataDirective::*,
     *   TaggingDirective?: null|TaggingDirective::*,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->acl = $input['ACL'] ?? null;
        $this->bucket = $input['Bucket'] ?? null;
        $this->cacheControl = $input['CacheControl'] ?? null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->contentDisposition = $input['ContentDisposition'] ?? null;
        $this->contentEncoding = $input['ContentEncoding'] ?? null;
        $this->contentLanguage = $input['ContentLanguage'] ?? null;
        $this->contentType = $input['ContentType'] ?? null;
        $this->copySource = $input['CopySource'] ?? null;
        $this->copySourceIfMatch = $input['CopySourceIfMatch'] ?? null;
        $this->copySourceIfModifiedSince = !isset($input['CopySourceIfModifiedSince']) ? null : ($input['CopySourceIfModifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfModifiedSince'] : new \DateTimeImmutable($input['CopySourceIfModifiedSince']));
        $this->copySourceIfNoneMatch = $input['CopySourceIfNoneMatch'] ?? null;
        $this->copySourceIfUnmodifiedSince = !isset($input['CopySourceIfUnmodifiedSince']) ? null : ($input['CopySourceIfUnmodifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfUnmodifiedSince'] : new \DateTimeImmutable($input['CopySourceIfUnmodifiedSince']));
        $this->expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->grantFullControl = $input['GrantFullControl'] ?? null;
        $this->grantRead = $input['GrantRead'] ?? null;
        $this->grantReadAcp = $input['GrantReadACP'] ?? null;
        $this->grantWriteAcp = $input['GrantWriteACP'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->metadata = $input['Metadata'] ?? null;
        $this->metadataDirective = $input['MetadataDirective'] ?? null;
        $this->taggingDirective = $input['TaggingDirective'] ?? null;
        $this->serverSideEncryption = $input['ServerSideEncryption'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->websiteRedirectLocation = $input['WebsiteRedirectLocation'] ?? null;
        $this->sseCustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->sseCustomerKey = $input['SSECustomerKey'] ?? null;
        $this->sseCustomerKeyMd5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->sseKmsKeyId = $input['SSEKMSKeyId'] ?? null;
        $this->sseKmsEncryptionContext = $input['SSEKMSEncryptionContext'] ?? null;
        $this->bucketKeyEnabled = $input['BucketKeyEnabled'] ?? null;
        $this->copySourceSseCustomerAlgorithm = $input['CopySourceSSECustomerAlgorithm'] ?? null;
        $this->copySourceSseCustomerKey = $input['CopySourceSSECustomerKey'] ?? null;
        $this->copySourceSseCustomerKeyMd5 = $input['CopySourceSSECustomerKeyMD5'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->tagging = $input['Tagging'] ?? null;
        $this->objectLockMode = $input['ObjectLockMode'] ?? null;
        $this->objectLockRetainUntilDate = !isset($input['ObjectLockRetainUntilDate']) ? null : ($input['ObjectLockRetainUntilDate'] instanceof \DateTimeImmutable ? $input['ObjectLockRetainUntilDate'] : new \DateTimeImmutable($input['ObjectLockRetainUntilDate']));
        $this->objectLockLegalHoldStatus = $input['ObjectLockLegalHoldStatus'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->expectedSourceBucketOwner = $input['ExpectedSourceBucketOwner'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   CopySource?: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
     *   Metadata?: null|array<string, string>,
     *   MetadataDirective?: null|MetadataDirective::*,
     *   TaggingDirective?: null|TaggingDirective::*,
     *   ServerSideEncryption?: null|ServerSideEncryption::*,
     *   StorageClass?: null|StorageClass::*,
     *   WebsiteRedirectLocation?: null|string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   SSEKMSKeyId?: null|string,
     *   SSEKMSEncryptionContext?: null|string,
     *   BucketKeyEnabled?: null|bool,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   Tagging?: null|string,
     *   ObjectLockMode?: null|ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: null|\DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: null|ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|CopyObjectRequest $input
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

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getCopySource(): ?string
    {
        return $this->copySource;
    }

    public function getCopySourceIfMatch(): ?string
    {
        return $this->copySourceIfMatch;
    }

    public function getCopySourceIfModifiedSince(): ?\DateTimeImmutable
    {
        return $this->copySourceIfModifiedSince;
    }

    public function getCopySourceIfNoneMatch(): ?string
    {
        return $this->copySourceIfNoneMatch;
    }

    public function getCopySourceIfUnmodifiedSince(): ?\DateTimeImmutable
    {
        return $this->copySourceIfUnmodifiedSince;
    }

    public function getCopySourceSseCustomerAlgorithm(): ?string
    {
        return $this->copySourceSseCustomerAlgorithm;
    }

    public function getCopySourceSseCustomerKey(): ?string
    {
        return $this->copySourceSseCustomerKey;
    }

    public function getCopySourceSseCustomerKeyMd5(): ?string
    {
        return $this->copySourceSseCustomerKeyMd5;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getExpectedSourceBucketOwner(): ?string
    {
        return $this->expectedSourceBucketOwner;
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
     * @return MetadataDirective::*|null
     */
    public function getMetadataDirective(): ?string
    {
        return $this->metadataDirective;
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

    /**
     * @return TaggingDirective::*|null
     */
    public function getTaggingDirective(): ?string
    {
        return $this->taggingDirective;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        return $this->websiteRedirectLocation;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->acl) {
            if (!ObjectCannedACL::exists($this->acl)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->acl));
            }
            $headers['x-amz-acl'] = $this->acl;
        }
        if (null !== $this->cacheControl) {
            $headers['Cache-Control'] = $this->cacheControl;
        }
        if (null !== $this->checksumAlgorithm) {
            if (!ChecksumAlgorithm::exists($this->checksumAlgorithm)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ChecksumAlgorithm" for "%s". The value "%s" is not a valid "ChecksumAlgorithm".', __CLASS__, $this->checksumAlgorithm));
            }
            $headers['x-amz-checksum-algorithm'] = $this->checksumAlgorithm;
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
        if (null !== $this->contentType) {
            $headers['Content-Type'] = $this->contentType;
        }
        if (null === $v = $this->copySource) {
            throw new InvalidArgument(\sprintf('Missing parameter "CopySource" for "%s". The value cannot be null.', __CLASS__));
        }
        $headers['x-amz-copy-source'] = $v;
        if (null !== $this->copySourceIfMatch) {
            $headers['x-amz-copy-source-if-match'] = $this->copySourceIfMatch;
        }
        if (null !== $this->copySourceIfModifiedSince) {
            $headers['x-amz-copy-source-if-modified-since'] = $this->copySourceIfModifiedSince->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->copySourceIfNoneMatch) {
            $headers['x-amz-copy-source-if-none-match'] = $this->copySourceIfNoneMatch;
        }
        if (null !== $this->copySourceIfUnmodifiedSince) {
            $headers['x-amz-copy-source-if-unmodified-since'] = $this->copySourceIfUnmodifiedSince->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->expires) {
            $headers['Expires'] = $this->expires->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
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
        if (null !== $this->metadataDirective) {
            if (!MetadataDirective::exists($this->metadataDirective)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "MetadataDirective" for "%s". The value "%s" is not a valid "MetadataDirective".', __CLASS__, $this->metadataDirective));
            }
            $headers['x-amz-metadata-directive'] = $this->metadataDirective;
        }
        if (null !== $this->taggingDirective) {
            if (!TaggingDirective::exists($this->taggingDirective)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "TaggingDirective" for "%s". The value "%s" is not a valid "TaggingDirective".', __CLASS__, $this->taggingDirective));
            }
            $headers['x-amz-tagging-directive'] = $this->taggingDirective;
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
        if (null !== $this->copySourceSseCustomerAlgorithm) {
            $headers['x-amz-copy-source-server-side-encryption-customer-algorithm'] = $this->copySourceSseCustomerAlgorithm;
        }
        if (null !== $this->copySourceSseCustomerKey) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key'] = $this->copySourceSseCustomerKey;
        }
        if (null !== $this->copySourceSseCustomerKeyMd5) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key-MD5'] = $this->copySourceSseCustomerKeyMd5;
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
        if (null !== $this->expectedSourceBucketOwner) {
            $headers['x-amz-source-expected-bucket-owner'] = $this->expectedSourceBucketOwner;
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
        $body = '';

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

    public function setContentType(?string $value): self
    {
        $this->contentType = $value;

        return $this;
    }

    public function setCopySource(?string $value): self
    {
        $this->copySource = $value;

        return $this;
    }

    public function setCopySourceIfMatch(?string $value): self
    {
        $this->copySourceIfMatch = $value;

        return $this;
    }

    public function setCopySourceIfModifiedSince(?\DateTimeImmutable $value): self
    {
        $this->copySourceIfModifiedSince = $value;

        return $this;
    }

    public function setCopySourceIfNoneMatch(?string $value): self
    {
        $this->copySourceIfNoneMatch = $value;

        return $this;
    }

    public function setCopySourceIfUnmodifiedSince(?\DateTimeImmutable $value): self
    {
        $this->copySourceIfUnmodifiedSince = $value;

        return $this;
    }

    public function setCopySourceSseCustomerAlgorithm(?string $value): self
    {
        $this->copySourceSseCustomerAlgorithm = $value;

        return $this;
    }

    public function setCopySourceSseCustomerKey(?string $value): self
    {
        $this->copySourceSseCustomerKey = $value;

        return $this;
    }

    public function setCopySourceSseCustomerKeyMd5(?string $value): self
    {
        $this->copySourceSseCustomerKeyMd5 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setExpectedSourceBucketOwner(?string $value): self
    {
        $this->expectedSourceBucketOwner = $value;

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
     * @param MetadataDirective::*|null $value
     */
    public function setMetadataDirective(?string $value): self
    {
        $this->metadataDirective = $value;

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

    /**
     * @param TaggingDirective::*|null $value
     */
    public function setTaggingDirective(?string $value): self
    {
        $this->taggingDirective = $value;

        return $this;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->websiteRedirectLocation = $value;

        return $this;
    }
}
