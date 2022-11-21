<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about a repository name and ID.
 */
final class RepositoryNameIdPair
{
    /**
     * The name associated with the repository.
     */
    private $repositoryName;

    /**
     * The ID associated with the repository.
     */
    private $repositoryId;

    /**
     * @param array{
     *   repositoryName?: null|string,
     *   repositoryId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->repositoryId = $input['repositoryId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
