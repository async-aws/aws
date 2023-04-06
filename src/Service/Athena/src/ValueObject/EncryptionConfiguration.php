<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\EncryptionOption;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * If query and calculation results are encrypted in Amazon S3, indicates the encryption option used (for example,
 * `SSE_KMS` or `CSE_KMS`) and key information. This is a client-side setting. If workgroup settings override
 * client-side settings, then the query uses the encryption configuration that is specified for the workgroup, and also
 * uses the location for storing query results specified in the workgroup. See
 * WorkGroupConfiguration$EnforceWorkGroupConfiguration and Workgroup Settings Override Client-Side Settings.
 *
 * @see https://docs.aws.amazon.com/athena/latest/ug/workgroups-settings-override.html
 */
final class EncryptionConfiguration
{
    /**
     * Indicates whether Amazon S3 server-side encryption with Amazon S3-managed keys (`SSE_S3`), server-side encryption
     * with KMS-managed keys (`SSE_KMS`), or client-side encryption with KMS-managed keys (`CSE_KMS`) is used.
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
