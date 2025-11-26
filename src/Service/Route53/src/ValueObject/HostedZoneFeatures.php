<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Route53\Enum\AcceleratedRecoveryStatus;

/**
 * Represents the features configuration for a hosted zone, including the status of various features and any associated
 * failure reasons.
 */
final class HostedZoneFeatures
{
    /**
     * The current status of accelerated recovery for the hosted zone.
     *
     * @var AcceleratedRecoveryStatus::*|null
     */
    private $acceleratedRecoveryStatus;

    /**
     * Information about any failures that occurred when attempting to enable or configure features for the hosted zone.
     *
     * @var HostedZoneFailureReasons|null
     */
    private $failureReasons;

    /**
     * @param array{
     *   AcceleratedRecoveryStatus?: AcceleratedRecoveryStatus::*|null,
     *   FailureReasons?: HostedZoneFailureReasons|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->acceleratedRecoveryStatus = $input['AcceleratedRecoveryStatus'] ?? null;
        $this->failureReasons = isset($input['FailureReasons']) ? HostedZoneFailureReasons::create($input['FailureReasons']) : null;
    }

    /**
     * @param array{
     *   AcceleratedRecoveryStatus?: AcceleratedRecoveryStatus::*|null,
     *   FailureReasons?: HostedZoneFailureReasons|array|null,
     * }|HostedZoneFeatures $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AcceleratedRecoveryStatus::*|null
     */
    public function getAcceleratedRecoveryStatus(): ?string
    {
        return $this->acceleratedRecoveryStatus;
    }

    public function getFailureReasons(): ?HostedZoneFailureReasons
    {
        return $this->failureReasons;
    }
}
