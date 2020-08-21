<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\BillingMode;

final class BillingModeSummary
{
    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
     */
    private $BillingMode;

    /**
     * Represents the time when `PAY_PER_REQUEST` was last set as the read/write capacity mode.
     */
    private $LastUpdateToPayPerRequestDateTime;

    /**
     * @param array{
     *   BillingMode?: null|BillingMode::*,
     *   LastUpdateToPayPerRequestDateTime?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->BillingMode = $input['BillingMode'] ?? null;
        $this->LastUpdateToPayPerRequestDateTime = $input['LastUpdateToPayPerRequestDateTime'] ?? null;
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
        return $this->BillingMode;
    }

    public function getLastUpdateToPayPerRequestDateTime(): ?\DateTimeImmutable
    {
        return $this->LastUpdateToPayPerRequestDateTime;
    }
}
