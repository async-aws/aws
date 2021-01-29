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
     * Represents the current state of server-side encryption. The only supported values are:.
     */
    private $status;

    /**
     * Server-side encryption type. The only supported value is:.
     */
    private $sseType;

    /**
     * The AWS KMS customer master key (CMK) ARN used for the AWS KMS encryption.
     */
    private $kmsMasterKeyArn;

    /**
     * Indicates the time, in UNIX epoch date format, when DynamoDB detected that the table's AWS KMS key was inaccessible.
     * This attribute will automatically be cleared when DynamoDB detects that the table's AWS KMS key is accessible again.
     * DynamoDB will initiate the table archival process when table's AWS KMS key remains inaccessible for more than seven
     * days from this date.
     */
    private $inaccessibleEncryptionDateTime;

    /**
     * @param array{
     *   Status?: null|SSEStatus::*,
     *   SSEType?: null|SSEType::*,
     *   KMSMasterKeyArn?: null|string,
     *   InaccessibleEncryptionDateTime?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['Status'] ?? null;
        $this->sseType = $input['SSEType'] ?? null;
        $this->kmsMasterKeyArn = $input['KMSMasterKeyArn'] ?? null;
        $this->inaccessibleEncryptionDateTime = $input['InaccessibleEncryptionDateTime'] ?? null;
    }

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
