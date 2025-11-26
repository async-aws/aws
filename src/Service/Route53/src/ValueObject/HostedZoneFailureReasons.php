<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * Contains information about why certain features failed to be enabled or configured for the hosted zone.
 */
final class HostedZoneFailureReasons
{
    /**
     * The reason why accelerated recovery failed to be enabled or disabled for the hosted zone, if applicable.
     *
     * @var string|null
     */
    private $acceleratedRecovery;

    /**
     * @param array{
     *   AcceleratedRecovery?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->acceleratedRecovery = $input['AcceleratedRecovery'] ?? null;
    }

    /**
     * @param array{
     *   AcceleratedRecovery?: string|null,
     * }|HostedZoneFailureReasons $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAcceleratedRecovery(): ?string
    {
        return $this->acceleratedRecovery;
    }
}
