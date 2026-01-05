<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3Vectors\Enum\SseType;

/**
 * The encryption configuration for a vector bucket or index. By default, if you don't specify, all new vectors in
 * Amazon S3 vector buckets use server-side encryption with Amazon S3 managed keys (SSE-S3), specifically `AES256`. You
 * can optionally override bucket level encryption settings, and set a specific encryption configuration for a vector
 * index at the time of index creation.
 */
final class EncryptionConfiguration
{
    /**
     * The server-side encryption type to use for the encryption configuration of the vector bucket. By default, if you
     * don't specify, all new vectors in Amazon S3 vector buckets use server-side encryption with Amazon S3 managed keys
     * (SSE-S3), specifically `AES256`.
     *
     * @var SseType::*|null
     */
    private $sseType;

    /**
     * Amazon Web Services Key Management Service (KMS) customer managed key ID to use for the encryption configuration.
     * This parameter is allowed if and only if `sseType` is set to `aws:kms`.
     *
     * To specify the KMS key, you must use the format of the KMS key Amazon Resource Name (ARN).
     *
     * For example, specify Key ARN in the following format:
     * `arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab`
     *
     * @var string|null
     */
    private $kmsKeyArn;

    /**
     * @param array{
     *   sseType?: SseType::*|null,
     *   kmsKeyArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sseType = $input['sseType'] ?? null;
        $this->kmsKeyArn = $input['kmsKeyArn'] ?? null;
    }

    /**
     * @param array{
     *   sseType?: SseType::*|null,
     *   kmsKeyArn?: string|null,
     * }|EncryptionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKeyArn(): ?string
    {
        return $this->kmsKeyArn;
    }

    /**
     * @return SseType::*|null
     */
    public function getSseType(): ?string
    {
        return $this->sseType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->sseType) {
            if (!SseType::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "sseType" for "%s". The value "%s" is not a valid "SseType".', __CLASS__, $v));
            }
            $payload['sseType'] = $v;
        }
        if (null !== $v = $this->kmsKeyArn) {
            $payload['kmsKeyArn'] = $v;
        }

        return $payload;
    }
}
