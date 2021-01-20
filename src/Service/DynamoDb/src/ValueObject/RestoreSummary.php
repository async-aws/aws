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
    private $SourceBackupArn;

    /**
     * The ARN of the source table of the backup that is being restored.
     */
    private $SourceTableArn;

    /**
     * Point in time or source backup time.
     */
    private $RestoreDateTime;

    /**
     * Indicates if a restore is in progress or not.
     */
    private $RestoreInProgress;

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
        $this->SourceBackupArn = $input['SourceBackupArn'] ?? null;
        $this->SourceTableArn = $input['SourceTableArn'] ?? null;
        $this->RestoreDateTime = $input['RestoreDateTime'] ?? null;
        $this->RestoreInProgress = $input['RestoreInProgress'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRestoreDateTime(): \DateTimeImmutable
    {
        return $this->RestoreDateTime;
    }

    public function getRestoreInProgress(): bool
    {
        return $this->RestoreInProgress;
    }

    public function getSourceBackupArn(): ?string
    {
        return $this->SourceBackupArn;
    }

    public function getSourceTableArn(): ?string
    {
        return $this->SourceTableArn;
    }
}
