<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class ProvisionedThroughputDescription
{
    /**
     * The date and time of the last provisioned throughput increase for this table.
     */
    private $LastIncreaseDateTime;

    /**
     * The date and time of the last provisioned throughput decrease for this table.
     */
    private $LastDecreaseDateTime;

    /**
     * The number of provisioned throughput decreases for this table during this UTC calendar day. For current maximums on
     * provisioned throughput decreases, see Service, Account, and Table Quotas in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     */
    private $NumberOfDecreasesToday;

    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * Eventually consistent reads require less effort than strongly consistent reads, so a setting of 50
     * `ReadCapacityUnits` per second provides 100 eventually consistent `ReadCapacityUnits` per second.
     */
    private $ReadCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`.
     */
    private $WriteCapacityUnits;

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
        $this->LastIncreaseDateTime = $input['LastIncreaseDateTime'] ?? null;
        $this->LastDecreaseDateTime = $input['LastDecreaseDateTime'] ?? null;
        $this->NumberOfDecreasesToday = $input['NumberOfDecreasesToday'] ?? null;
        $this->ReadCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->WriteCapacityUnits = $input['WriteCapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLastDecreaseDateTime(): ?\DateTimeImmutable
    {
        return $this->LastDecreaseDateTime;
    }

    public function getLastIncreaseDateTime(): ?\DateTimeImmutable
    {
        return $this->LastIncreaseDateTime;
    }

    public function getNumberOfDecreasesToday(): ?string
    {
        return $this->NumberOfDecreasesToday;
    }

    public function getReadCapacityUnits(): ?string
    {
        return $this->ReadCapacityUnits;
    }

    public function getWriteCapacityUnits(): ?string
    {
        return $this->WriteCapacityUnits;
    }
}
