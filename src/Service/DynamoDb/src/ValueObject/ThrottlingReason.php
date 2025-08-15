<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the specific reason why a DynamoDB request was throttled and the ARN of the impacted resource. This helps
 * identify exactly what resource is being throttled, what type of operation caused it, and why the throttling occurred.
 */
final class ThrottlingReason
{
    /**
     * The reason for throttling. The throttling reason follows a specific format: `ResourceType+OperationType+LimitType`:
     *
     * - Resource Type (What is being throttled): Table or Index
     * - Operation Type (What kind of operation): Read or Write
     * - Limit Type (Why the throttling occurred):
     *
     *   - `ProvisionedThroughputExceeded`: The request rate is exceeding the provisioned throughput capacity [^1] (read or
     *     write capacity units) configured for a table or a global secondary index (GSI) in provisioned capacity mode.
     *   - `AccountLimitExceeded`: The request rate has caused a table or global secondary index (GSI) in on-demand mode to
     *     exceed the per-table account-level service quotas [^2] for read/write throughput in the current Amazon Web
     *     Services Region.
     *   - `KeyRangeThroughputExceeded`: The request rate directed at a specific partition key value has exceeded the
     *     internal partition-level throughput limits [^3], indicating uneven access patterns across the table's or GSI's
     *     key space.
     *   - `MaxOnDemandThroughputExceeded`: The request rate has exceeded the configured maximum throughput limits [^4] set
     *     for a table or index in on-demand capacity mode.
     *
     *
     * Examples of complete throttling reasons:
     *
     * - TableReadProvisionedThroughputExceeded
     * - IndexWriteAccountLimitExceeded
     *
     * This helps identify exactly what resource is being throttled, what type of operation caused it, and why the
     * throttling occurred.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/provisioned-capacity-mode.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ServiceQuotas.html#default-limits-throughput
     * [^3]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/bp-partition-key-design.html
     * [^4]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/on-demand-capacity-mode-max-throughput.html
     *
     * @var string|null
     */
    private $reason;

    /**
     * The Amazon Resource Name (ARN) of the DynamoDB table or index that experienced the throttling event.
     *
     * @var string|null
     */
    private $resource;

    /**
     * @param array{
     *   reason?: null|string,
     *   resource?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reason = $input['reason'] ?? null;
        $this->resource = $input['resource'] ?? null;
    }

    /**
     * @param array{
     *   reason?: null|string,
     *   resource?: null|string,
     * }|ThrottlingReason $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }
}
