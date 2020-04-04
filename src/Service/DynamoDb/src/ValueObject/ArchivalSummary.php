<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class ArchivalSummary
{
    /**
     * The date and time when table archival was initiated by DynamoDB, in UNIX epoch time format.
     */
    private $ArchivalDateTime;

    /**
     * The reason DynamoDB archived the table. Currently, the only possible value is:.
     */
    private $ArchivalReason;

    /**
     * The Amazon Resource Name (ARN) of the backup the table was archived to, when applicable in the archival reason. If
     * you wish to restore this backup to the same table name, you will need to delete the original table.
     */
    private $ArchivalBackupArn;

    /**
     * @param array{
     *   ArchivalDateTime?: null|\DateTimeImmutable,
     *   ArchivalReason?: null|string,
     *   ArchivalBackupArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ArchivalDateTime = $input['ArchivalDateTime'] ?? null;
        $this->ArchivalReason = $input['ArchivalReason'] ?? null;
        $this->ArchivalBackupArn = $input['ArchivalBackupArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArchivalBackupArn(): ?string
    {
        return $this->ArchivalBackupArn;
    }

    public function getArchivalDateTime(): ?\DateTimeImmutable
    {
        return $this->ArchivalDateTime;
    }

    public function getArchivalReason(): ?string
    {
        return $this->ArchivalReason;
    }
}
