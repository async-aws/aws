<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Contains details of a table archival operation.
 */
final class ArchivalSummary
{
    /**
     * The date and time when table archival was initiated by DynamoDB, in UNIX epoch time format.
     *
     * @var \DateTimeImmutable|null
     */
    private $archivalDateTime;

    /**
     * The reason DynamoDB archived the table. Currently, the only possible value is:
     *
     * - `INACCESSIBLE_ENCRYPTION_CREDENTIALS` - The table was archived due to the table's KMS key being inaccessible for
     *   more than seven days. An On-Demand backup was created at the archival time.
     *
     * @var string|null
     */
    private $archivalReason;

    /**
     * The Amazon Resource Name (ARN) of the backup the table was archived to, when applicable in the archival reason. If
     * you wish to restore this backup to the same table name, you will need to delete the original table.
     *
     * @var string|null
     */
    private $archivalBackupArn;

    /**
     * @param array{
     *   ArchivalDateTime?: \DateTimeImmutable|null,
     *   ArchivalReason?: string|null,
     *   ArchivalBackupArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->archivalDateTime = $input['ArchivalDateTime'] ?? null;
        $this->archivalReason = $input['ArchivalReason'] ?? null;
        $this->archivalBackupArn = $input['ArchivalBackupArn'] ?? null;
    }

    /**
     * @param array{
     *   ArchivalDateTime?: \DateTimeImmutable|null,
     *   ArchivalReason?: string|null,
     *   ArchivalBackupArn?: string|null,
     * }|ArchivalSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArchivalBackupArn(): ?string
    {
        return $this->archivalBackupArn;
    }

    public function getArchivalDateTime(): ?\DateTimeImmutable
    {
        return $this->archivalDateTime;
    }

    public function getArchivalReason(): ?string
    {
        return $this->archivalReason;
    }
}
