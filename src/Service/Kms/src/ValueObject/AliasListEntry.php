<?php

namespace AsyncAws\Kms\ValueObject;

/**
 * Contains information about an alias.
 */
final class AliasListEntry
{
    /**
     * String that contains the alias. This value begins with `alias/`.
     */
    private $aliasName;

    /**
     * String that contains the key ARN.
     */
    private $aliasArn;

    /**
     * String that contains the key identifier of the KMS key associated with the alias.
     */
    private $targetKeyId;

    /**
     * Date and time that the alias was most recently created in the account and Region. Formatted as Unix time.
     */
    private $creationDate;

    /**
     * Date and time that the alias was most recently associated with a KMS key in the account and Region. Formatted as Unix
     * time.
     */
    private $lastUpdatedDate;

    /**
     * @param array{
     *   AliasName?: null|string,
     *   AliasArn?: null|string,
     *   TargetKeyId?: null|string,
     *   CreationDate?: null|\DateTimeImmutable,
     *   LastUpdatedDate?: null|\DateTimeImmutable,
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
