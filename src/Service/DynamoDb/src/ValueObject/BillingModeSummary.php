<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\BillingMode;

/**
 * Contains the details for the read/write capacity mode.
 */
final class BillingModeSummary
{
    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
     */
    private $billingMode;

    /**
     * Represents the time when `PAY_PER_REQUEST` was last set as the read/write capacity mode.
     */
    private $lastUpdateToPayPerRequestDateTime;

    /**
     * @param array{
     *   BillingMode?: null|BillingMode::*,
     *   LastUpdateToPayPerRequestDateTime?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->billingMode = $input['BillingMode'] ?? null;
        $this->lastUpdateToPayPerRequestDateTime = $input['LastUpdateToPayPerRequestDateTime'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BillingMode::*|null
     */
    public function getBillingMode(): ?string
    {
        return $this->billingMode;
    }

    public function getLastUpdateToPayPerRequestDateTime(): ?\DateTimeImmutable
    {
        return $this->lastUpdateToPayPerRequestDateTime;
    }
}
