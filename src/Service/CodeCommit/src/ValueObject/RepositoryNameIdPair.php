<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Information about a repository name and ID.
 */
final class RepositoryNameIdPair
{
    /**
     * The name associated with the repository.
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The ID associated with the repository.
     *
     * @var string|null
     */
    private $repositoryId;

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   repositoryId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->repositoryId = $input['repositoryId'] ?? null;
    }

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   repositoryId?: string|null,
     * }|RepositoryNameIdPair $input
     */
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
