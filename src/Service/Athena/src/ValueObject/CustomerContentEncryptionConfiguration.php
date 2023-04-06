<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Specifies the KMS key that is used to encrypt the user's data stores in Athena.
 */
final class CustomerContentEncryptionConfiguration
{
    /**
     * The KMS key that is used to encrypt the user's data stores in Athena.
     */
    private $kmsKey;

    /**
     * @param array{
     *   KmsKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->kmsKey = $input['KmsKey'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKey(): string
    {
        return $this->kmsKey;
    }
}
