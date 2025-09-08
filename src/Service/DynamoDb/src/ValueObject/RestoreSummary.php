<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains details for the restore.
 */
final class RestoreSummary
{
    /**
     * The Amazon Resource Name (ARN) of the backup from which the table was restored.
     *
     * @var string|null
     */
    private $sourceBackupArn;

    /**
     * The ARN of the source table of the backup that is being restored.
     *
     * @var string|null
     */
    private $sourceTableArn;

    /**
     * Point in time or source backup time.
     *
     * @var \DateTimeImmutable
     */
    private $restoreDateTime;

    /**
     * Indicates if a restore is in progress or not.
     *
     * @var bool
     */
    private $restoreInProgress;

    /**
     * @param array{
     *   SourceBackupArn?: string|null,
     *   SourceTableArn?: string|null,
     *   RestoreDateTime: \DateTimeImmutable,
     *   RestoreInProgress: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sourceBackupArn = $input['SourceBackupArn'] ?? null;
        $this->sourceTableArn = $input['SourceTableArn'] ?? null;
        $this->restoreDateTime = $input['RestoreDateTime'] ?? $this->throwException(new InvalidArgument('Missing required field "RestoreDateTime".'));
        $this->restoreInProgress = $input['RestoreInProgress'] ?? $this->throwException(new InvalidArgument('Missing required field "RestoreInProgress".'));
    }

    /**
     * @param array{
     *   SourceBackupArn?: string|null,
     *   SourceTableArn?: string|null,
     *   RestoreDateTime: \DateTimeImmutable,
     *   RestoreInProgress: bool,
     * }|RestoreSummary $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
