<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration settings for delivering logs to Amazon S3 buckets.
 */
final class S3LoggingConfiguration
{
    /**
     * Enables S3 log delivery.
     *
     * @var bool
     */
    private $enabled;

    /**
     * The KMS key ARN to encrypt the logs published to the given Amazon S3 destination.
     *
     * @var string|null
     */
    private $kmsKey;

    /**
     * The Amazon S3 destination URI for log publishing.
     *
     * @var string|null
     */
    private $logLocation;

    /**
     * @param array{
     *   Enabled: bool,
     *   KmsKey?: string|null,
     *   LogLocation?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->kmsKey = $input['KmsKey'] ?? null;
        $this->logLocation = $input['LogLocation'] ?? null;
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   KmsKey?: string|null,
     *   LogLocation?: string|null,
     * }|S3LoggingConfiguration $input
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

    public function getLogLocation(): ?string
    {
        return $this->logLocation;
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
        if (null !== $v = $this->logLocation) {
            $payload['LogLocation'] = $v;
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
