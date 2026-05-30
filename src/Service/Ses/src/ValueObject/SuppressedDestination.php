<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Ses\Enum\SuppressionListReason;

/**
 * An object that contains information about an email address that is on the suppression list for your account or for a
 * specific tenant.
 */
final class SuppressedDestination
{
    /**
     * The email address that is on the suppression list for your account or for a specific tenant.
     *
     * @var string
     */
    private $emailAddress;

    /**
     * The reason that the address was added to the suppression list for your account or for a specific tenant.
     *
     * @var SuppressionListReason::*
     */
    private $reason;

    /**
     * The date and time when the suppressed destination was last updated, shown in Unix time format.
     *
     * @var \DateTimeImmutable
     */
    private $lastUpdateTime;

    /**
     * An optional value that can contain additional information about the reasons that the address was added to the
     * suppression list for your account or for a specific tenant.
     *
     * @var SuppressedDestinationAttributes|null
     */
    private $attributes;

    /**
     * The name of the tenant that the suppressed destination belongs to. This field is present only when the suppressed
     * destination is on a tenant's suppression list.
     *
     * @var string|null
     */
    private $tenantName;

    /**
     * @param array{
     *   EmailAddress: string,
     *   Reason: SuppressionListReason::*,
     *   LastUpdateTime: \DateTimeImmutable,
     *   Attributes?: SuppressedDestinationAttributes|array|null,
     *   TenantName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->emailAddress = $input['EmailAddress'] ?? $this->throwException(new InvalidArgument('Missing required field "EmailAddress".'));
        $this->reason = $input['Reason'] ?? $this->throwException(new InvalidArgument('Missing required field "Reason".'));
        $this->lastUpdateTime = $input['LastUpdateTime'] ?? $this->throwException(new InvalidArgument('Missing required field "LastUpdateTime".'));
        $this->attributes = isset($input['Attributes']) ? SuppressedDestinationAttributes::create($input['Attributes']) : null;
        $this->tenantName = $input['TenantName'] ?? null;
    }

    /**
     * @param array{
     *   EmailAddress: string,
     *   Reason: SuppressionListReason::*,
     *   LastUpdateTime: \DateTimeImmutable,
     *   Attributes?: SuppressedDestinationAttributes|array|null,
     *   TenantName?: string|null,
     * }|SuppressedDestination $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributes(): ?SuppressedDestinationAttributes
    {
        return $this->attributes;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getLastUpdateTime(): \DateTimeImmutable
    {
        return $this->lastUpdateTime;
    }

    /**
     * @return SuppressionListReason::*
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    public function getTenantName(): ?string
    {
        return $this->tenantName;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
