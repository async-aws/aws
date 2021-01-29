<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * The provisioned throughput settings for the table, consisting of read and write capacity units, along with data about
 * increases and decreases.
 */
final class ProvisionedThroughputDescription
{
    /**
     * The date and time of the last provisioned throughput increase for this table.
     */
    private $lastIncreaseDateTime;

    /**
     * The date and time of the last provisioned throughput decrease for this table.
     */
    private $lastDecreaseDateTime;

    /**
     * The number of provisioned throughput decreases for this table during this UTC calendar day. For current maximums on
     * provisioned throughput decreases, see Service, Account, and Table Quotas in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     */
    private $numberOfDecreasesToday;

    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * Eventually consistent reads require less effort than strongly consistent reads, so a setting of 50
     * `ReadCapacityUnits` per second provides 100 eventually consistent `ReadCapacityUnits` per second.
     */
    private $readCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`.
     */
    private $writeCapacityUnits;

    /**
     * @param array{
     *   LastIncreaseDateTime?: null|\DateTimeImmutable,
     *   LastDecreaseDateTime?: null|\DateTimeImmutable,
     *   NumberOfDecreasesToday?: null|string,
     *   ReadCapacityUnits?: null|string,
     *   WriteCapacityUnits?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->lastIncreaseDateTime = $input['LastIncreaseDateTime'] ?? null;
        $this->lastDecreaseDateTime = $input['LastDecreaseDateTime'] ?? null;
        $this->numberOfDecreasesToday = $input['NumberOfDecreasesToday'] ?? null;
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLastDecreaseDateTime(): ?\DateTimeImmutable
    {
        return $this->lastDecreaseDateTime;
    }

    public function getLastIncreaseDateTime(): ?\DateTimeImmutable
    {
        return $this->lastIncreaseDateTime;
    }

    public function getNumberOfDecreasesToday(): ?string
    {
        return $this->numberOfDecreasesToday;
    }

    public function getReadCapacityUnits(): ?string
    {
        return $this->readCapacityUnits;
    }

    public function getWriteCapacityUnits(): ?string
    {
        return $this->writeCapacityUnits;
    }
}
