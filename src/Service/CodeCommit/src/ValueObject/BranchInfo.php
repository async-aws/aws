<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Returns information about a branch.
 */
final class BranchInfo
{
    /**
     * The name of the branch.
     *
     * @var string|null
     */
    private $branchName;

    /**
     * The ID of the last commit made to the branch.
     *
     * @var string|null
     */
    private $commitId;

    /**
     * @param array{
     *   branchName?: string|null,
     *   commitId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->branchName = $input['branchName'] ?? null;
        $this->commitId = $input['commitId'] ?? null;
    }

    /**
     * @param array{
     *   branchName?: string|null,
     *   commitId?: string|null,
     * }|BranchInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBranchName(): ?string
    {
        return $this->branchName;
    }

    public function getCommitId(): ?string
    {
        return $this->commitId;
    }
}
