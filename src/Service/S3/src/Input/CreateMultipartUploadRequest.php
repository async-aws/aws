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

final class CreateMultipartUploadRequest extends Input
{
    /**
     * The canned ACL to apply to the object. Amazon S3 supports a set of predefined ACLs, known as *canned ACLs*. Each
     * canned ACL has a predefined set of grantees and permissions. For more information, see Canned ACL [^1] in the *Amazon
     * S3 User Guide*.
     *
     * By default, all objects are private. Only the owner has full access control. When uploading an object, you can grant
     * access permissions to individual Amazon Web Services accounts or to predefined groups defined by Amazon S3. These
     * permissions are then added to the access control list (ACL) on the new object. For more information, see Using ACLs
     * [^2]. One way to grant the permissions using the request headers is to specify a canned ACL with the `x-amz-acl`
     * request header.
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/dev/S3_ACLs_UsingACLs.html
     *
     * @var ObjectCannedACL::*|null
     */
    private $acl;

    /**
     * The name of the bucket where the multipart upload is initiated and where the object is uploaded.
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
     * Specifies caching behavior along the request/reply chain.
     *
     * @var string|null
     */
    private $cacheControl;

    /**
     * Specifies presentational information for the object.
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
     * The language that the content is in.
     *
     * @var string|null
     */
    private $contentLanguage;

    /**
     * A standard MIME type describing the format of the object data.
     *
     * @var string|null
     */
    private $contentType;

    /**
     * The date and time at which the object is no longer cacheable.
     *
     * @var \DateTimeImmutable|null
     */
    private $expires;

    /**
     * Specify access permissions explicitly to give the grantee READ, READ_ACP, and WRITE_ACP permissions on the object.
     *
     * By default, all objects are private. Only the owner has full access control. When uploading an object, you can use
     * this header to explicitly grant access permissions to specific Amazon Web Services accounts or groups. This header
     * maps to specific permissions that Amazon S3 supports in an ACL. For more information, see Access Control List (ACL)
     * Overview [^1] in the *Amazon S3 User Guide*.
     *
     * You specify each grantee as a type=value pair, where the type is one of the following:
     *
     * - `id` – if the value specified is the canonical user ID of an Amazon Web Services account
     * - `uri` – if you are granting permissions to a predefined group
     * - `emailAddress` – if the value specified is the email address of an Amazon Web Services account
     *
     *   > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *   >
     *   > - US East (N. Virginia)
     *   > - US West (N. California)
     *   > - US West (Oregon)
     *   > - Asia Pacific (Singapore)
     *   > - Asia Pacific (Sydney)
     *   > - Asia Pacific (Tokyo)
     *   > - Europe (Ireland)
     *   > - South America (São Paulo)
     *   >
     *   > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^2] in the Amazon Web
     *   > Services General Reference.
     *
     *
     * For example, the following `x-amz-grant-read` header grants the Amazon Web Services accounts identified by account
     * IDs permissions to read object data and its metadata:
     *
     * `x-amz-grant-read: id="11112222333", id="444455556666" `
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^2]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     *
     * @var string|null
     */
    private $grantFullControl;

    /**
     * Specify access permissions explicitly to allow grantee to read the object data and its metadata.
     *
     * By default, all objects are private. Only the owner has full access control. When uploading an object, you can use
     * this header to explicitly grant access permissions to specific Amazon Web Services accounts or groups. This header
     * maps to specific permissions that Amazon S3 supports in an ACL. For more information, see Access Control List (ACL)
     * Overview [^1] in the *Amazon S3 User Guide*.
     *
     * You specify each grantee as a type=value pair, where the type is one of the following:
     *
     * - `id` – if the value specified is the canonical user ID of an Amazon Web Services account
     * - `uri` – if you are granting permissions to a predefined group
     * - `emailAddress` – if the value specified is the email address of an Amazon Web Services account
     *
     *   > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *   >
     *   > - US East (N. Virginia)
     *   > - US West (N. California)
     *   > - US West (Oregon)
     *   > - Asia Pacific (Singapore)
     *   > - Asia Pacific (Sydney)
     *   > - Asia Pacific (Tokyo)
     *   > - Europe (Ireland)
     *   > - South America (São Paulo)
     *   >
     *   > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^2] in the Amazon Web
     *   > Services General Reference.
     *
     *
     * For example, the following `x-amz-grant-read` header grants the Amazon Web Services accounts identified by account
     * IDs permissions to read object data and its metadata:
     *
     * `x-amz-grant-read: id="11112222333", id="444455556666" `
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^2]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     *
     * @var string|null
     */
    private $grantRead;

    /**
     * Specify access permissions explicitly to allows grantee to read the object ACL.
     *
     * By default, all objects are private. Only the owner has full access control. When uploading an object, you can use
     * this header to explicitly grant access permissions to specific Amazon Web Services accounts or groups. This header
     * maps to specific permissions that Amazon S3 supports in an ACL. For more information, see Access Control List (ACL)
     * Overview [^1] in the *Amazon S3 User Guide*.
     *
     * You specify each grantee as a type=value pair, where the type is one of the following:
     *
     * - `id` – if the value specified is the canonical user ID of an Amazon Web Services account
     * - `uri` – if you are granting permissions to a predefined group
     * - `emailAddress` – if the value specified is the email address of an Amazon Web Services account
     *
     *   > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *   >
     *   > - US East (N. Virginia)
     *   > - US West (N. California)
     *   > - US West (Oregon)
     *   > - Asia Pacific (Singapore)
     *   > - Asia Pacific (Sydney)
     *   > - Asia Pacific (Tokyo)
     *   > - Europe (Ireland)
     *   > - South America (São Paulo)
     *   >
     *   > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^2] in the Amazon Web
     *   > Services General Reference.
     *
     *
     * For example, the following `x-amz-grant-read` header grants the Amazon Web Services accounts identified by account
     * IDs permissions to read object data and its metadata:
     *
     * `x-amz-grant-read: id="11112222333", id="444455556666" `
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^2]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     *
     * @var string|null
     */
    private $grantReadAcp;

    /**
     * Specify access permissions explicitly to allows grantee to allow grantee to write the ACL for the applicable object.
     *
     * By default, all objects are private. Only the owner has full access control. When uploading an object, you can use
     * this header to explicitly grant access permissions to specific Amazon Web Services accounts or groups. This header
     * maps to specific permissions that Amazon S3 supports in an ACL. For more information, see Access Control List (ACL)
     * Overview [^1] in the *Amazon S3 User Guide*.
     *
     * You specify each grantee as a type=value pair, where the type is one of the following:
     *
     * - `id` – if the value specified is the canonical user ID of an Amazon Web Services account
     * - `uri` – if you are granting permissions to a predefined group
     * - `emailAddress` – if the value specified is the email address of an Amazon Web Services account
     *
     *   > Using email addresses to specify a grantee is only supported in the following Amazon Web Services Regions:
     *   >
     *   > - US East (N. Virginia)
     *   > - US West (N. California)
     *   > - US West (Oregon)
     *   > - Asia Pacific (Singapore)
     *   > - Asia Pacific (Sydney)
     *   > - Asia Pacific (Tokyo)
     *   > - Europe (Ireland)
     *   > - South America (São Paulo)
     *   >
     *   > For a list of all the Amazon S3 supported Regions and endpoints, see Regions and Endpoints [^2] in the Amazon Web
     *   > Services General Reference.
     *
     *
     * For example, the following `x-amz-grant-read` header grants the Amazon Web Services accounts identified by account
     * IDs permissions to read object data and its metadata:
     *
     * `x-amz-grant-read: id="11112222333", id="444455556666" `
     *
     * > - This functionality is not supported for directory buckets.
     * > - This functionality is not supported for Amazon S3 on Outposts.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html
     * [^2]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     *
     * @var string|null
     */
    private $grantWriteAcp;

    /**
     * Object key for which the multipart upload is to be initiated.
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
     * The server-side encryption algorithm used when you store this object in Amazon S3 (for example, `AES256`, `aws:kms`).
     *
     * - **Directory buckets ** - For directory buckets, there are only two supported options for server-side encryption:
     *   server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) and server-side encryption with KMS keys
     *   (SSE-KMS) (`aws:kms`). We recommend that the bucket's default encryption uses the desired encryption configuration
     *   and you don't override the bucket default encryption in your `CreateSession` requests or `PUT` object requests.
     *   Then, new objects are automatically encrypted with the desired encryption settings. For more information, see
     *   Protecting data with server-side encryption [^1] in the *Amazon S3 User Guide*. For more information about the
     *   encryption overriding behaviors in directory buckets, see Specifying server-side encryption with KMS for new object
     *   uploads [^2].
     *
     *   In the Zonal endpoint API calls (except CopyObject [^3] and UploadPartCopy [^4]) using the REST API, the encryption
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
     *   > [^5] and UploadPartCopy [^6]), the encryption request headers must match the default encryption configuration of
     *   > the directory bucket.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-serv-side-encryption.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-express-specifying-kms-encryption.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^6]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
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
     * to an external URL. Amazon S3 stores the value of this header in the object metadata.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $websiteRedirectLocation;

    /**
     * Specifies the algorithm to use when encrypting the object (for example, AES256).
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
     * Specifies the 128-bit MD5 digest of the customer-provided encryption key according to RFC 1321. Amazon S3 uses this
     * header for a message integrity check to ensure that the encryption key was transmitted without error.
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
     * Specifies the Amazon Web Services KMS Encryption Context to use for object encryption. The value of this header is a
     * Base64-encoded string of a UTF-8 encoded JSON, which contains the encryption context as key-value pairs.
     *
     * **Directory buckets** - You can optionally provide an explicit encryption context value. The value must match the
     * default encryption context - the bucket Amazon Resource Name (ARN). An additional encryption context value is not
     * supported.
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
     * The tag-set for the object. The tag-set must be encoded as URL Query parameters.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $tagging;

    /**
     * Specifies the Object Lock mode that you want to apply to the uploaded object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockMode::*|null
     */
    private $objectLockMode;

    /**
     * Specifies the date and time when you want the Object Lock to expire.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $objectLockRetainUntilDate;

    /**
     * Specifies whether you want to apply a legal hold to the uploaded object.
     *
     * > This functionality is not supported for directory buckets.
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
     * Indicates the algorithm that you want Amazon S3 to use to create the checksum for the object. For more information,
     * see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
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
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->acl = $input['ACL'] ?? null;
        $this->bucket = $input['Bucket'] ?? null;
        $this->cacheControl = $input['CacheControl'] ?? null;
        $this->contentDisposition = $input['ContentDisposition'] ?? null;
        $this->contentEncoding = $input['ContentEncoding'] ?? null;
        $this->contentLanguage = $input['ContentLanguage'] ?? null;
        $this->contentType = $input['ContentType'] ?? null;
        $this->expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->grantFullControl = $input['GrantFullControl'] ?? null;
        $this->grantRead = $input['GrantRead'] ?? null;
        $this->grantReadAcp = $input['GrantReadACP'] ?? null;
        $this->grantWriteAcp = $input['GrantWriteACP'] ?? null;
        $this->key = $input['Key'] ?? null;
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
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ACL?: null|ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: null|string,
     *   ContentDisposition?: null|string,
     *   ContentEncoding?: null|string,
     *   ContentLanguage?: null|string,
     *   ContentType?: null|string,
     *   Expires?: null|\DateTimeImmutable|string,
     *   GrantFullControl?: null|string,
     *   GrantRead?: null|string,
     *   GrantReadACP?: null|string,
     *   GrantWriteACP?: null|string,
     *   Key?: string,
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
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   '@region'?: string|null,
     * }|CreateMultipartUploadRequest $input
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
        if (null !== $this->checksumAlgorithm) {
            if (!ChecksumAlgorithm::exists($this->checksumAlgorithm)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ChecksumAlgorithm" for "%s". The value "%s" is not a valid "ChecksumAlgorithm".', __CLASS__, $this->checksumAlgorithm));
            }
            $headers['x-amz-checksum-algorithm'] = $this->checksumAlgorithm;
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
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key'])) . '?uploads';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
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
}
