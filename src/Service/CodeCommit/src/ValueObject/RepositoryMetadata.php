<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about a repository.
 */
final class RepositoryMetadata
{
    /**
     * The ID of the Amazon Web Services account associated with the repository.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * The ID of the repository.
     *
     * @var string|null
     */
    private $repositoryId;

    /**
     * The repository's name.
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * A comment or description about the repository.
     *
     * @var string|null
     */
    private $repositoryDescription;

    /**
     * The repository's default branch name.
     *
     * @var string|null
     */
    private $defaultBranch;

    /**
     * The date and time the repository was last modified, in timestamp format.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModifiedDate;

    /**
     * The date and time the repository was created, in timestamp format.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * The URL to use for cloning the repository over HTTPS.
     *
     * @var string|null
     */
    private $cloneUrlHttp;

    /**
     * The URL to use for cloning the repository over SSH.
     *
     * @var string|null
     */
    private $cloneUrlSsh;

    /**
     * The Amazon Resource Name (ARN) of the repository.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The ID of the Key Management Service encryption key used to encrypt and decrypt the repository.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * @param array{
     *   accountId?: string|null,
     *   repositoryId?: string|null,
     *   repositoryName?: string|null,
     *   repositoryDescription?: string|null,
     *   defaultBranch?: string|null,
     *   lastModifiedDate?: \DateTimeImmutable|null,
     *   creationDate?: \DateTimeImmutable|null,
     *   cloneUrlHttp?: string|null,
     *   cloneUrlSsh?: string|null,
     *   Arn?: string|null,
     *   kmsKeyId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accountId = $input['accountId'] ?? null;
        $this->repositoryId = $input['repositoryId'] ?? null;
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->repositoryDescription = $input['repositoryDescription'] ?? null;
        $this->defaultBranch = $input['defaultBranch'] ?? null;
        $this->lastModifiedDate = $input['lastModifiedDate'] ?? null;
        $this->creationDate = $input['creationDate'] ?? null;
        $this->cloneUrlHttp = $input['cloneUrlHttp'] ?? null;
        $this->cloneUrlSsh = $input['cloneUrlSsh'] ?? null;
        $this->arn = $input['Arn'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
    }

    /**
     * @param array{
     *   accountId?: string|null,
     *   repositoryId?: string|null,
     *   repositoryName?: string|null,
     *   repositoryDescription?: string|null,
     *   defaultBranch?: string|null,
     *   lastModifiedDate?: \DateTimeImmutable|null,
     *   creationDate?: \DateTimeImmutable|null,
     *   cloneUrlHttp?: string|null,
     *   cloneUrlSsh?: string|null,
     *   Arn?: string|null,
     *   kmsKeyId?: string|null,
     * }|RepositoryMetadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCloneUrlHttp(): ?string
    {
        return $this->cloneUrlHttp;
    }

    public function getCloneUrlSsh(): ?string
    {
        return $this->cloneUrlSsh;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getDefaultBranch(): ?string
    {
        return $this->defaultBranch;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getLastModifiedDate(): ?\DateTimeImmutable
    {
        return $this->lastModifiedDate;
    }

    public function getRepositoryDescription(): ?string
    {
        return $this->repositoryDescription;
    }

    public function getRepositoryId(): ?string
    {
        return $this->repositoryId;
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }
}
