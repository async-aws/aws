<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\EncryptionOption;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * If query and calculation results are encrypted in Amazon S3, indicates the encryption option used (for example,
 * `SSE_KMS` or `CSE_KMS`) and key information.
 */
final class EncryptionConfiguration
{
    /**
     * Indicates whether Amazon S3 server-side encryption with Amazon S3-managed keys (`SSE_S3`), server-side encryption
     * with KMS-managed keys (`SSE_KMS`), or client-side encryption with KMS-managed keys (`CSE_KMS`) is used.
     *
     * If a query runs in a workgroup and the workgroup overrides client-side settings, then the workgroup's setting for
     * encryption is used. It specifies whether query results must be encrypted, for all queries that run in this workgroup.
     */
    private $encryptionOption;

    /**
     * For `SSE_KMS` and `CSE_KMS`, this is the KMS key ARN or ID.
     */
    private $kmsKey;

    /**
     * @param array{
     *   EncryptionOption: EncryptionOption::*,
     *   KmsKey?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->encryptionOption = $input['EncryptionOption'] ?? null;
        $this->kmsKey = $input['KmsKey'] ?? null;
    }

    /**
     * @param array{
     *   EncryptionOption: EncryptionOption::*,
     *   KmsKey?: null|string,
     * }|EncryptionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EncryptionOption::*
     */
    public function getEncryptionOption(): string
    {
        return $this->encryptionOption;
    }

    public function getKmsKey(): ?string
    {
        return $this->kmsKey;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->encryptionOption) {
            throw new InvalidArgument(sprintf('Missing parameter "EncryptionOption" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!EncryptionOption::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "EncryptionOption" for "%s". The value "%s" is not a valid "EncryptionOption".', __CLASS__, $v));
        }
        $payload['EncryptionOption'] = $v;
        if (null !== $v = $this->kmsKey) {
            $payload['KmsKey'] = $v;
        }

        return $payload;
    }
}
