<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\EncryptionType;

/**
 * A bucket-level setting for Amazon S3 general purpose buckets used to prevent the upload of new objects encrypted with
 * the specified server-side encryption type. For example, blocking an encryption type will block `PutObject`,
 * `CopyObject`, `PostObject`, multipart upload, and replication requests to the bucket for objects with the specified
 * encryption type. However, you can continue to read and list any pre-existing objects already encrypted with the
 * specified encryption type. For more information, see Blocking or unblocking SSE-C for a general purpose bucket [^1].
 *
 * This data type is used with the following actions:
 *
 * - PutBucketEncryption [^2]
 * - GetBucketEncryption [^3]
 * - DeleteBucketEncryption [^4]
 *
 * - `Permissions`:
 *
 *   You must have the `s3:PutEncryptionConfiguration` permission to block or unblock an encryption type for a bucket.
 *
 *   You must have the `s3:GetEncryptionConfiguration` permission to view a bucket's encryption type.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/blocking-unblocking-s3-c-encryption-gpb.html
 * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketEncryption.html
 * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketEncryption.html
 * [^4]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_DeleteBucketEncryption.html
 */
final class BlockedEncryptionTypes
{
    /**
     * The object encryption type that you want to block or unblock for an Amazon S3 general purpose bucket.
     *
     * > Currently, this parameter only supports blocking or unblocking server side encryption with customer-provided keys
     * > (SSE-C). For more information about SSE-C, see Using server-side encryption with customer-provided keys (SSE-C)
     * > [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/ServerSideEncryptionCustomerKeys.html
     *
     * @var list<EncryptionType::*>|null
     */
    private $encryptionType;

    /**
     * @param array{
     *   EncryptionType?: array<EncryptionType::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->encryptionType = $input['EncryptionType'] ?? null;
    }

    /**
     * @param array{
     *   EncryptionType?: array<EncryptionType::*>|null,
     * }|BlockedEncryptionTypes $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<EncryptionType::*>
     */
    public function getEncryptionType(): array
    {
        return $this->encryptionType ?? [];
    }
}
