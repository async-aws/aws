<?php

namespace AsyncAws\SecretsManager\ValueObject;

use AsyncAws\SecretsManager\Enum\StatusType;

/**
 * A replication object consisting of a `RegionReplicationStatus` object and includes a Region, KMSKeyId, status, and
 * status message.
 */
final class ReplicationStatusType
{
    /**
     * The Region where replication occurs.
     */
    private $region;

    /**
     * Can be an `ARN`, `Key ID`, or `Alias`.
     */
    private $kmsKeyId;

    /**
     * The status can be `InProgress`, `Failed`, or `InSync`.
     */
    private $status;

    /**
     * Status message such as "*Secret with this name already exists in this region*".
     */
    private $statusMessage;

    /**
     * The date that you last accessed the secret in the Region.
     */
    private $lastAccessedDate;

    /**
     * @param array{
     *   Region?: null|string,
     *   KmsKeyId?: null|string,
     *   Status?: null|StatusType::*,
     *   StatusMessage?: null|string,
     *   LastAccessedDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['Region'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->statusMessage = $input['StatusMessage'] ?? null;
        $this->lastAccessedDate = $input['LastAccessedDate'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getLastAccessedDate(): ?\DateTimeImmutable
    {
        return $this->lastAccessedDate;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return StatusType::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getStatusMessage(): ?string
    {
        return $this->statusMessage;
    }
}
