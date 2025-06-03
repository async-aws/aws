<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * If you encrypt query and calculation results in Athena owned storage, this field indicates the encryption option (for
 * example, SSE_KMS or CSE_KMS) and key information.
 */
final class ManagedQueryResultsEncryptionConfiguration
{
    /**
     * The ARN of an KMS key for encrypting managed query results.
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
     * }|ManagedQueryResultsEncryptionConfiguration $input
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
