<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Contains information about an alias.
 */
final class AliasListEntry
{
    /**
     * String that contains the alias. This value begins with `alias/`.
     *
     * @var string|null
     */
    private $aliasName;

    /**
     * String that contains the key ARN.
     *
     * @var string|null
     */
    private $aliasArn;

    /**
     * String that contains the key identifier of the KMS key associated with the alias.
     *
     * @var string|null
     */
    private $targetKeyId;

    /**
     * Date and time that the alias was most recently created in the account and Region. Formatted as Unix time.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * Date and time that the alias was most recently associated with a KMS key in the account and Region. Formatted as Unix
     * time.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastUpdatedDate;

    /**
     * @param array{
     *   AliasName?: string|null,
     *   AliasArn?: string|null,
     *   TargetKeyId?: string|null,
     *   CreationDate?: \DateTimeImmutable|null,
     *   LastUpdatedDate?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->aliasName = $input['AliasName'] ?? null;
        $this->aliasArn = $input['AliasArn'] ?? null;
        $this->targetKeyId = $input['TargetKeyId'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->lastUpdatedDate = $input['LastUpdatedDate'] ?? null;
    }

    /**
     * @param array{
     *   AliasName?: string|null,
     *   AliasArn?: string|null,
     *   TargetKeyId?: string|null,
     *   CreationDate?: \DateTimeImmutable|null,
     *   LastUpdatedDate?: \DateTimeImmutable|null,
     * }|AliasListEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliasArn(): ?string
    {
        return $this->aliasArn;
    }

    public function getAliasName(): ?string
    {
        return $this->aliasName;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getLastUpdatedDate(): ?\DateTimeImmutable
    {
        return $this->lastUpdatedDate;
    }

    public function getTargetKeyId(): ?string
    {
        return $this->targetKeyId;
    }
}
