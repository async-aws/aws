<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\SSEStatus;
use AsyncAws\DynamoDb\Enum\SSEType;

class SSEDescription
{
    /**
     * Represents the current state of server-side encryption. The only supported values are:.
     */
    private $Status;

    /**
     * Server-side encryption type. The only supported value is:.
     */
    private $SSEType;

    /**
     * The AWS KMS customer master key (CMK) ARN used for the AWS KMS encryption.
     */
    private $KMSMasterKeyArn;

    /**
     * Indicates the time, in UNIX epoch date format, when DynamoDB detected that the table's AWS KMS key was inaccessible.
     * This attribute will automatically be cleared when DynamoDB detects that the table's AWS KMS key is accessible again.
     * DynamoDB will initiate the table archival process when table's AWS KMS key remains inaccessible for more than seven
     * days from this date.
     */
    private $InaccessibleEncryptionDateTime;

    /**
     * @param array{
     *   Status?: null|\AsyncAws\DynamoDb\Enum\SSEStatus::*,
     *   SSEType?: null|\AsyncAws\DynamoDb\Enum\SSEType::*,
     *   KMSMasterKeyArn?: null|string,
     *   InaccessibleEncryptionDateTime?: null|\DateTimeInterface,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Status = $input['Status'] ?? null;
        $this->SSEType = $input['SSEType'] ?? null;
        $this->KMSMasterKeyArn = $input['KMSMasterKeyArn'] ?? null;
        $this->InaccessibleEncryptionDateTime = $input['InaccessibleEncryptionDateTime'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInaccessibleEncryptionDateTime(): ?\DateTimeInterface
    {
        return $this->InaccessibleEncryptionDateTime;
    }

    public function getKMSMasterKeyArn(): ?string
    {
        return $this->KMSMasterKeyArn;
    }

    /**
     * @return SSEType::*|null
     */
    public function getSSEType(): ?string
    {
        return $this->SSEType;
    }

    /**
     * @return SSEStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->Status;
    }
}
