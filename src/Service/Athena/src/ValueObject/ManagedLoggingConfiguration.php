<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration settings for delivering logs to Amazon S3 buckets.
 */
final class ManagedLoggingConfiguration
{
    /**
     * Enables mamanged log persistence.
     *
     * @var bool
     */
    private $enabled;

    /**
     * The KMS key ARN to encrypt the logs stored in managed log persistence.
     *
     * @var string|null
     */
    private $kmsKey;

    /**
     * @param array{
     *   Enabled: bool,
     *   KmsKey?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->kmsKey = $input['KmsKey'] ?? null;
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   KmsKey?: string|null,
     * }|ManagedLoggingConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
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
        $v = $this->enabled;
        $payload['Enabled'] = (bool) $v;
        if (null !== $v = $this->kmsKey) {
            $payload['KmsKey'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
