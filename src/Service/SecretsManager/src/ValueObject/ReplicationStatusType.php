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
     *
     * @var string|null
     */
    private $region;

    /**
     * Can be an `ARN`, `Key ID`, or `Alias`.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The status can be `InProgress`, `Failed`, or `InSync`.
     *
     * @var StatusType::*|null
     */
    private $status;

    /**
     * Status message such as "*Secret with this name already exists in this region*".
     *
     * @var string|null
     */
    private $statusMessage;

    /**
     * The date that the secret was last accessed in the Region. This field is omitted if the secret has never been
     * retrieved in the Region.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastAccessedDate;

    /**
     * @param array{
     *   Region?: string|null,
     *   KmsKeyId?: string|null,
     *   Status?: StatusType::*|null,
     *   StatusMessage?: string|null,
     *   LastAccessedDate?: \DateTimeImmutable|null,
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

    /**
     * @param array{
     *   Region?: string|null,
     *   KmsKeyId?: string|null,
     *   Status?: StatusType::*|null,
     *   StatusMessage?: string|null,
     *   LastAccessedDate?: \DateTimeImmutable|null,
     * }|ReplicationStatusType $input
     */
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
