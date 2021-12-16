<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the location of application artifacts stored in GitHub.
 */
final class GitHubLocation
{
    /**
     * The GitHub account and repository pair that stores a reference to the commit that represents the bundled artifacts
     * for the application revision.
     */
    private $repository;

    /**
     * The SHA1 commit ID of the GitHub commit that represents the bundled artifacts for the application revision.
     */
    private $commitId;

    /**
     * @param array{
     *   repository?: null|string,
     *   commitId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->repository = $input['repository'] ?? null;
        $this->commitId = $input['commitId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCommitId(): ?string
    {
        return $this->commitId;
    }

    public function getRepository(): ?string
    {
        return $this->repository;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->repository) {
            $payload['repository'] = $v;
        }
        if (null !== $v = $this->commitId) {
            $payload['commitId'] = $v;
        }

        return $payload;
    }
}
