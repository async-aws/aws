<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The configuration for storing results in Athena owned storage, which includes whether this feature is enabled;
 * whether encryption configuration, if any, is used for encrypting query results.
 */
final class ManagedQueryResultsConfiguration
{
    /**
     * If set to true, allows you to store query results in Athena owned storage. If set to false, workgroup member stores
     * query results in location specified under `ResultConfiguration$OutputLocation`. The default is false. A workgroup
     * cannot have the `ResultConfiguration$OutputLocation` parameter when you set this field to true.
     *
     * @var bool
     */
    private $enabled;

    /**
     * If you encrypt query and calculation results in Athena owned storage, this field indicates the encryption option (for
     * example, SSE_KMS or CSE_KMS) and key information.
     *
     * @var ManagedQueryResultsEncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * @param array{
     *   Enabled: bool,
     *   EncryptionConfiguration?: null|ManagedQueryResultsEncryptionConfiguration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->encryptionConfiguration = isset($input['EncryptionConfiguration']) ? ManagedQueryResultsEncryptionConfiguration::create($input['EncryptionConfiguration']) : null;
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   EncryptionConfiguration?: null|ManagedQueryResultsEncryptionConfiguration|array,
     * }|ManagedQueryResultsConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getEncryptionConfiguration(): ?ManagedQueryResultsEncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
