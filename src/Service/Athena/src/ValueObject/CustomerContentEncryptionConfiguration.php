<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies the customer managed KMS key that is used to encrypt the user's data stores in Athena. When an Amazon Web
 * Services managed key is used, this value is null. This setting does not apply to Athena SQL workgroups.
 */
final class CustomerContentEncryptionConfiguration
{
    /**
     * The customer managed KMS key that is used to encrypt the user's data stores in Athena.
     *
     * @var string
     */
    private $kmsKey;

    /**
     * @param array{
     *   KmsKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->kmsKey = $input['KmsKey'] ?? $this->throwException(new InvalidArgument('Missing required field "KmsKey".'));
    }

    /**
     * @param array{
     *   KmsKey: string,
     * }|CustomerContentEncryptionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKey(): string
    {
        return $this->kmsKey;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
