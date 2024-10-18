<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\ServerSideEncryption;

/**
 * Describes the default server-side encryption to apply to new objects in the bucket. If a PUT Object request doesn't
 * specify any server-side encryption, this default encryption will be applied. For more information, see
 * PutBucketEncryption [^1].
 *
 * > - **General purpose buckets** - If you don't specify a customer managed key at configuration, Amazon S3
 * >   automatically creates an Amazon Web Services KMS key (`aws/s3`) in your Amazon Web Services account the first
 * >   time that you add an object encrypted with SSE-KMS to a bucket. By default, Amazon S3 uses this KMS key for
 * >   SSE-KMS.
 * > - **Directory buckets** - Your SSE-KMS configuration can only support 1 customer managed key [^2] per directory
 * >   bucket for the lifetime of the bucket. The Amazon Web Services managed key [^3] (`aws/s3`) isn't supported.
 * > - **Directory buckets** - For directory buckets, there are only two supported options for server-side encryption:
 * >   SSE-S3 and SSE-KMS.
 * >
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTBucketPUTencryption.html
 * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
 * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#aws-managed-cmk
 */
final class ServerSideEncryptionByDefault
{
    /**
     * Server-side encryption algorithm to use for the default encryption.
     *
     * > For directory buckets, there are only two supported values for server-side encryption: `AES256` and `aws:kms`.
     *
     * @var ServerSideEncryption::*
     */
    private $sseAlgorithm;

    /**
     * Amazon Web Services Key Management Service (KMS) customer managed key ID to use for the default encryption.
     *
     * > - **General purpose buckets** - This parameter is allowed if and only if `SSEAlgorithm` is set to `aws:kms` or
     * >   `aws:kms:dsse`.
     * > - **Directory buckets** - This parameter is allowed if and only if `SSEAlgorithm` is set to `aws:kms`.
     * >
     *
     * You can specify the key ID, key alias, or the Amazon Resource Name (ARN) of the KMS key.
     *
     * - Key ID: `1234abcd-12ab-34cd-56ef-1234567890ab`
     * - Key ARN: `arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab`
     * - Key Alias: `alias/alias-name`
     *
     * If you are using encryption with cross-account or Amazon Web Services service operations, you must use a fully
     * qualified KMS key ARN. For more information, see Using encryption for cross-account operations [^1].
     *
     * > - **General purpose buckets** - If you're specifying a customer managed KMS key, we recommend using a fully
     * >   qualified KMS key ARN. If you use a KMS key alias instead, then KMS resolves the key within the requester’s
     * >   account. This behavior can result in data that's encrypted with a KMS key that belongs to the requester, and not
     * >   the bucket owner. Also, if you use a key ID, you can run into a LogDestination undeliverable error when creating
     * >   a VPC flow log.
     * > - **Directory buckets** - When you specify an KMS customer managed key [^2] for encryption in your directory
     * >   bucket, only use the key ID or key ARN. The key alias format of the KMS key isn't supported.
     * >
     *
     * ! Amazon S3 only supports symmetric encryption KMS keys. For more information, see Asymmetric keys in Amazon Web
     * ! Services KMS [^3] in the *Amazon Web Services Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/bucket-encryption.html#bucket-encryption-update-bucket-policy
     * [^2]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#customer-cmk
     * [^3]: https://docs.aws.amazon.com/kms/latest/developerguide/symmetric-asymmetric.html
     *
     * @var string|null
     */
    private $kmsMasterKeyId;

    /**
     * @param array{
     *   SSEAlgorithm: ServerSideEncryption::*,
     *   KMSMasterKeyID?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sseAlgorithm = $input['SSEAlgorithm'] ?? $this->throwException(new InvalidArgument('Missing required field "SSEAlgorithm".'));
        $this->kmsMasterKeyId = $input['KMSMasterKeyID'] ?? null;
    }

    /**
     * @param array{
     *   SSEAlgorithm: ServerSideEncryption::*,
     *   KMSMasterKeyID?: null|string,
     * }|ServerSideEncryptionByDefault $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsMasterKeyId(): ?string
    {
        return $this->kmsMasterKeyId;
    }

    /**
     * @return ServerSideEncryption::*
     */
    public function getSseAlgorithm(): string
    {
        return $this->sseAlgorithm;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
