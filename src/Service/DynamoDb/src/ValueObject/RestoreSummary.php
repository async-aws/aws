<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Contains details for the restore.
 */
final class RestoreSummary
{
    /**
     * The Amazon Resource Name (ARN) of the backup from which the table was restored.
     */
    private $sourceBackupArn;

    /**
     * The ARN of the source table of the backup that is being restored.
     */
    private $sourceTableArn;

    /**
     * Point in time or source backup time.
     */
    private $restoreDateTime;

    /**
     * Indicates if a restore is in progress or not.
     */
    private $restoreInProgress;

    /**
     * @param array{
     *   SourceBackupArn?: null|string,
     *   SourceTableArn?: null|string,
     *   RestoreDateTime: \DateTimeImmutable,
     *   RestoreInProgress: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceBackupArn = $input['SourceBackupArn'] ?? null;
        $this->sourceTableArn = $input['SourceTableArn'] ?? null;
        $this->restoreDateTime = $input['RestoreDateTime'] ?? null;
        $this->restoreInProgress = $input['RestoreInProgress'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRestoreDateTime(): \DateTimeImmutable
    {
        return $this->restoreDateTime;
    }

    public function getRestoreInProgress(): bool
    {
        return $this->restoreInProgress;
    }

    public function getSourceBackupArn(): ?string
    {
        return $this->sourceBackupArn;
    }

    public function getSourceTableArn(): ?string
    {
        return $this->sourceTableArn;
    }
}
