<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\SSEStatus;
use AsyncAws\DynamoDb\Enum\SSEType;

/**
 * The description of the server-side encryption status on the specified table.
 */
final class SSEDescription
{
    /**
     * Represents the current state of server-side encryption. The only supported values are:
     *
     * - `ENABLED` - Server-side encryption is enabled.
     * - `UPDATING` - Server-side encryption is being updated.
     *
     * @var SSEStatus::*|null
     */
    private $status;

    /**
     * Server-side encryption type. The only supported value is:
     *
     * - `KMS` - Server-side encryption that uses Key Management Service. The key is stored in your account and is managed
     *   by KMS (KMS charges apply).
     *
     * @var SSEType::*|null
     */
    private $sseType;

    /**
     * The KMS key ARN used for the KMS encryption.
     *
     * @var string|null
     */
    private $kmsMasterKeyArn;

    /**
     * Indicates the time, in UNIX epoch date format, when DynamoDB detected that the table's KMS key was inaccessible. This
     * attribute will automatically be cleared when DynamoDB detects that the table's KMS key is accessible again. DynamoDB
     * will initiate the table archival process when table's KMS key remains inaccessible for more than seven days from this
     * date.
     *
     * @var \DateTimeImmutable|null
     */
    private $inaccessibleEncryptionDateTime;

    /**
     * @param array{
     *   Status?: SSEStatus::*|null,
     *   SSEType?: SSEType::*|null,
     *   KMSMasterKeyArn?: string|null,
     *   InaccessibleEncryptionDateTime?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['Status'] ?? null;
        $this->sseType = $input['SSEType'] ?? null;
        $this->kmsMasterKeyArn = $input['KMSMasterKeyArn'] ?? null;
        $this->inaccessibleEncryptionDateTime = $input['InaccessibleEncryptionDateTime'] ?? null;
    }

    /**
     * @param array{
     *   Status?: SSEStatus::*|null,
     *   SSEType?: SSEType::*|null,
     *   KMSMasterKeyArn?: string|null,
     *   InaccessibleEncryptionDateTime?: \DateTimeImmutable|null,
     * }|SSEDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInaccessibleEncryptionDateTime(): ?\DateTimeImmutable
    {
        return $this->inaccessibleEncryptionDateTime;
    }

    public function getKmsMasterKeyArn(): ?string
    {
        return $this->kmsMasterKeyArn;
    }

    /**
     * @return SSEType::*|null
     */
    public function getSseType(): ?string
    {
        return $this->sseType;
    }

    /**
     * @return SSEStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
