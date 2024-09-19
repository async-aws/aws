<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Specifies the default server-side encryption configuration.
 *
 * > - **General purpose buckets** - If you're specifying a customer managed KMS key, we recommend using a fully
 * >   qualified KMS key ARN. If you use a KMS key alias instead, then KMS resolves the key within the requester’s
 * >   account. This behavior can result in data that's encrypted with a KMS key that belongs to the requester, and not
 * >   the bucket owner.
 * > - **Directory buckets** - When you specify an KMS customer managed key [^1] for encryption in your directory
 * >   bucket, only use the key ID or key ARN. The key alias format of the KMS key isn't supported.
 * >
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
 */
final class ServerSideEncryptionRule
{
    /**
     * Specifies the default server-side encryption to apply to new objects in the bucket. If a PUT Object request doesn't
     * specify any server-side encryption, this default encryption will be applied.
     *
     * @var ServerSideEncryptionByDefault|null
     */
    private $applyServerSideEncryptionByDefault;

    /**
     * Specifies whether Amazon S3 should use an S3 Bucket Key with server-side encryption using KMS (SSE-KMS) for new
     * objects in the bucket. Existing objects are not affected. Setting the `BucketKeyEnabled` element to `true` causes
     * Amazon S3 to use an S3 Bucket Key.
     *
     * > - **General purpose buckets** - By default, S3 Bucket Key is not enabled. For more information, see Amazon S3
     * >   Bucket Keys [^1] in the *Amazon S3 User Guide*.
     * > - **Directory buckets** - S3 Bucket Keys are always enabled for `GET` and `PUT` operations in a directory bucket
     * >   and can’t be disabled. S3 Bucket Keys aren't supported, when you copy SSE-KMS encrypted objects from general
     * >   purpose buckets to directory buckets, from directory buckets to general purpose buckets, or between directory
     * >   buckets, through CopyObject [^2], UploadPartCopy [^3], the Copy operation in Batch Operations [^4], or the import
     * >   jobs [^5]. In this case, Amazon S3 makes a call to KMS every time a copy request is made for a KMS-encrypted
     * >   object.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/bucket-key.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_UploadPartCopy.html
     * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-objects-Batch-Ops
     * [^5]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/create-import-job
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * @param array{
     *   ApplyServerSideEncryptionByDefault?: null|ServerSideEncryptionByDefault|array,
     *   BucketKeyEnabled?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->applyServerSideEncryptionByDefault = isset($input['ApplyServerSideEncryptionByDefault']) ? ServerSideEncryptionByDefault::create($input['ApplyServerSideEncryptionByDefault']) : null;
        $this->bucketKeyEnabled = $input['BucketKeyEnabled'] ?? null;
    }

    /**
     * @param array{
     *   ApplyServerSideEncryptionByDefault?: null|ServerSideEncryptionByDefault|array,
     *   BucketKeyEnabled?: null|bool,
     * }|ServerSideEncryptionRule $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApplyServerSideEncryptionByDefault(): ?ServerSideEncryptionByDefault
    {
        return $this->applyServerSideEncryptionByDefault;
    }

    public function getBucketKeyEnabled(): ?bool
    {
        return $this->bucketKeyEnabled;
    }
}
