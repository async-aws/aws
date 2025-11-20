<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\TenantIsolationMode;

/**
 * Specifies the tenant isolation mode configuration for a Lambda function. This allows you to configure specific tenant
 * isolation strategies for your function invocations. Tenant isolation configuration cannot be modified after function
 * creation.
 */
final class TenancyConfig
{
    /**
     * Tenant isolation mode allows for invocation to be sent to a corresponding execution environment dedicated to a
     * specific tenant ID.
     *
     * @var TenantIsolationMode::*
     */
    private $tenantIsolationMode;

    /**
     * @param array{
     *   TenantIsolationMode: TenantIsolationMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tenantIsolationMode = $input['TenantIsolationMode'] ?? $this->throwException(new InvalidArgument('Missing required field "TenantIsolationMode".'));
    }

    /**
     * @param array{
     *   TenantIsolationMode: TenantIsolationMode::*,
     * }|TenancyConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return TenantIsolationMode::*
     */
    public function getTenantIsolationMode(): string
    {
        return $this->tenantIsolationMode;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
