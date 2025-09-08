<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\BillingMode;

/**
 * Contains the details for the read/write capacity mode. This page talks about `PROVISIONED` and `PAY_PER_REQUEST`
 * billing modes. For more information about these modes, see Read/write capacity mode [^1].
 *
 * > You may need to switch to on-demand mode at least once in order to return a `BillingModeSummary` response.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.ReadWriteCapacityMode.html
 */
final class BillingModeSummary
{
    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
     *
     * - `PROVISIONED` - Sets the read/write capacity mode to `PROVISIONED`. We recommend using `PROVISIONED` for
     *   predictable workloads.
     * - `PAY_PER_REQUEST` - Sets the read/write capacity mode to `PAY_PER_REQUEST`. We recommend using `PAY_PER_REQUEST`
     *   for unpredictable workloads.
     *
     * @var BillingMode::*|null
     */
    private $billingMode;

    /**
     * Represents the time when `PAY_PER_REQUEST` was last set as the read/write capacity mode.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastUpdateToPayPerRequestDateTime;

    /**
     * @param array{
     *   BillingMode?: BillingMode::*|null,
     *   LastUpdateToPayPerRequestDateTime?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->billingMode = $input['BillingMode'] ?? null;
        $this->lastUpdateToPayPerRequestDateTime = $input['LastUpdateToPayPerRequestDateTime'] ?? null;
    }

    /**
     * @param array{
     *   BillingMode?: BillingMode::*|null,
     *   LastUpdateToPayPerRequestDateTime?: \DateTimeImmutable|null,
     * }|BillingModeSummary $input
     */
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
