<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about the newly created repository.
 */
final class RepositoryMetadata
{
    /**
     * The ID of the AWS account associated with the repository.
     */
    private $accountId;

    /**
     * The ID of the repository.
     */
    private $repositoryId;

    /**
     * The repository's name.
     */
    private $repositoryName;

    /**
     * A comment or description about the repository.
     */
    private $repositoryDescription;

    /**
     * The repository's default branch name.
     */
    private $defaultBranch;

    /**
     * The date and time the repository was last modified, in timestamp format.
     */
    private $lastModifiedDate;

    /**
     * The date and time the repository was created, in timestamp format.
     */
    private $creationDate;

    /**
     * The URL to use for cloning the repository over HTTPS.
     */
    private $cloneUrlHttp;

    /**
     * The URL to use for cloning the repository over SSH.
     */
    private $cloneUrlSsh;

    /**
     * The Amazon Resource Name (ARN) of the repository.
     */
    private $arn;

    /**
     * @param array{
     *   accountId?: null|string,
     *   repositoryId?: null|string,
     *   repositoryName?: null|string,
     *   repositoryDescription?: null|string,
     *   defaultBranch?: null|string,
     *   lastModifiedDate?: null|\DateTimeImmutable,
     *   creationDate?: null|\DateTimeImmutable,
     *   cloneUrlHttp?: null|string,
     *   cloneUrlSsh?: null|string,
     *   Arn?: null|string,
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
    }

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
