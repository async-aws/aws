<?php

namespace AsyncAws\CodeCommit\ValueObject;

/**
 * Returns information about a specific commit.
 */
final class Commit
{
    /**
     * The full SHA ID of the specified commit.
     *
     * @var string|null
     */
    private $commitId;

    /**
     * Tree information for the specified commit.
     *
     * @var string|null
     */
    private $treeId;

    /**
     * A list of parent commits for the specified commit. Each parent commit ID is the full commit ID.
     *
     * @var string[]|null
     */
    private $parents;

    /**
     * The commit message associated with the specified commit.
     *
     * @var string|null
     */
    private $message;

    /**
     * Information about the author of the specified commit. Information includes the date in timestamp format with GMT
     * offset, the name of the author, and the email address for the author, as configured in Git.
     *
     * @var UserInfo|null
     */
    private $author;

    /**
     * Information about the person who committed the specified commit, also known as the committer. Information includes
     * the date in timestamp format with GMT offset, the name of the committer, and the email address for the committer, as
     * configured in Git.
     *
     * For more information about the difference between an author and a committer in Git, see Viewing the Commit History
     * [^1] in Pro Git by Scott Chacon and Ben Straub.
     *
     * [^1]: http://git-scm.com/book/ch2-3.html
     *
     * @var UserInfo|null
     */
    private $committer;

    /**
     * Any other data associated with the specified commit.
     *
     * @var string|null
     */
    private $additionalData;

    /**
     * @param array{
     *   commitId?: string|null,
     *   treeId?: string|null,
     *   parents?: string[]|null,
     *   message?: string|null,
     *   author?: UserInfo|array|null,
     *   committer?: UserInfo|array|null,
     *   additionalData?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->commitId = $input['commitId'] ?? null;
        $this->treeId = $input['treeId'] ?? null;
        $this->parents = $input['parents'] ?? null;
        $this->message = $input['message'] ?? null;
        $this->author = isset($input['author']) ? UserInfo::create($input['author']) : null;
        $this->committer = isset($input['committer']) ? UserInfo::create($input['committer']) : null;
        $this->additionalData = $input['additionalData'] ?? null;
    }

    /**
     * @param array{
     *   commitId?: string|null,
     *   treeId?: string|null,
     *   parents?: string[]|null,
     *   message?: string|null,
     *   author?: UserInfo|array|null,
     *   committer?: UserInfo|array|null,
     *   additionalData?: string|null,
     * }|Commit $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdditionalData(): ?string
    {
        return $this->additionalData;
    }

    public function getAuthor(): ?UserInfo
    {
        return $this->author;
    }

    public function getCommitId(): ?string
    {
        return $this->commitId;
    }

    public function getCommitter(): ?UserInfo
    {
        return $this->committer;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return string[]
     */
    public function getParents(): array
    {
        return $this->parents ?? [];
    }

    public function getTreeId(): ?string
    {
        return $this->treeId;
    }
}
