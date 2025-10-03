<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetDifferencesInput extends Input
{
    /**
     * The name of the repository where you want to get differences.
     *
     * @required
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The branch, tag, HEAD, or other fully qualified reference used to identify a commit (for example, the full commit
     * ID). Optional. If not specified, all changes before the `afterCommitSpecifier` value are shown. If you do not use
     * `beforeCommitSpecifier` in your request, consider limiting the results with `maxResults`.
     *
     * @var string|null
     */
    private $beforeCommitSpecifier;

    /**
     * The branch, tag, HEAD, or other fully qualified reference used to identify a commit.
     *
     * @required
     *
     * @var string|null
     */
    private $afterCommitSpecifier;

    /**
     * The file path in which to check for differences. Limits the results to this path. Can also be used to specify the
     * previous name of a directory or folder. If `beforePath` and `afterPath` are not specified, differences are shown for
     * all paths.
     *
     * @var string|null
     */
    private $beforePath;

    /**
     * The file path in which to check differences. Limits the results to this path. Can also be used to specify the changed
     * name of a directory or folder, if it has changed. If not specified, differences are shown for all paths.
     *
     * @var string|null
     */
    private $afterPath;

    /**
     * A non-zero, non-negative integer used to limit the number of returned results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * An enumeration token that, when provided in a request, returns the next batch of the results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   repositoryName?: string,
     *   beforeCommitSpecifier?: string|null,
     *   afterCommitSpecifier?: string,
     *   beforePath?: string|null,
     *   afterPath?: string|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->beforeCommitSpecifier = $input['beforeCommitSpecifier'] ?? null;
        $this->afterCommitSpecifier = $input['afterCommitSpecifier'] ?? null;
        $this->beforePath = $input['beforePath'] ?? null;
        $this->afterPath = $input['afterPath'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string,
     *   beforeCommitSpecifier?: string|null,
     *   afterCommitSpecifier?: string,
     *   beforePath?: string|null,
     *   afterPath?: string|null,
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|GetDifferencesInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAfterCommitSpecifier(): ?string
    {
        return $this->afterCommitSpecifier;
    }

    public function getAfterPath(): ?string
    {
        return $this->afterPath;
    }

    public function getBeforeCommitSpecifier(): ?string
    {
        return $this->beforeCommitSpecifier;
    }

    public function getBeforePath(): ?string
    {
        return $this->beforePath;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'CodeCommit_20150413.GetDifferences',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAfterCommitSpecifier(?string $value): self
    {
        $this->afterCommitSpecifier = $value;

        return $this;
    }

    public function setAfterPath(?string $value): self
    {
        $this->afterPath = $value;

        return $this;
    }

    public function setBeforeCommitSpecifier(?string $value): self
    {
        $this->beforeCommitSpecifier = $value;

        return $this;
    }

    public function setBeforePath(?string $value): self
    {
        $this->beforePath = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setRepositoryName(?string $value): self
    {
        $this->repositoryName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->repositoryName) {
            throw new InvalidArgument(\sprintf('Missing parameter "repositoryName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['repositoryName'] = $v;
        if (null !== $v = $this->beforeCommitSpecifier) {
            $payload['beforeCommitSpecifier'] = $v;
        }
        if (null === $v = $this->afterCommitSpecifier) {
            throw new InvalidArgument(\sprintf('Missing parameter "afterCommitSpecifier" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['afterCommitSpecifier'] = $v;
        if (null !== $v = $this->beforePath) {
            $payload['beforePath'] = $v;
        }
        if (null !== $v = $this->afterPath) {
            $payload['afterPath'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
