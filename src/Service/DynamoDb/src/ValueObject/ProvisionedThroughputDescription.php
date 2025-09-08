<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the provisioned throughput settings for the table, consisting of read and write capacity units, along with
 * data about increases and decreases.
 */
final class ProvisionedThroughputDescription
{
    /**
     * The date and time of the last provisioned throughput increase for this table.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastIncreaseDateTime;

    /**
     * The date and time of the last provisioned throughput decrease for this table.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastDecreaseDateTime;

    /**
     * The number of provisioned throughput decreases for this table during this UTC calendar day. For current maximums on
     * provisioned throughput decreases, see Service, Account, and Table Quotas [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     *
     * @var int|null
     */
    private $numberOfDecreasesToday;

    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * Eventually consistent reads require less effort than strongly consistent reads, so a setting of 50
     * `ReadCapacityUnits` per second provides 100 eventually consistent `ReadCapacityUnits` per second.
     *
     * @var int|null
     */
    private $readCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`.
     *
     * @var int|null
     */
    private $writeCapacityUnits;

    /**
     * @param array{
     *   LastIncreaseDateTime?: \DateTimeImmutable|null,
     *   LastDecreaseDateTime?: \DateTimeImmutable|null,
     *   NumberOfDecreasesToday?: int|null,
     *   ReadCapacityUnits?: int|null,
     *   WriteCapacityUnits?: int|null,
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

    /**
     * @param array{
     *   LastIncreaseDateTime?: \DateTimeImmutable|null,
     *   LastDecreaseDateTime?: \DateTimeImmutable|null,
     *   NumberOfDecreasesToday?: int|null,
     *   ReadCapacityUnits?: int|null,
     *   WriteCapacityUnits?: int|null,
     * }|ProvisionedThroughputDescription $input
     */
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

    public function getNumberOfDecreasesToday(): ?int
    {
        return $this->numberOfDecreasesToday;
    }

    public function getReadCapacityUnits(): ?int
    {
        return $this->readCapacityUnits;
    }

    public function getWriteCapacityUnits(): ?int
    {
        return $this->writeCapacityUnits;
    }
}
