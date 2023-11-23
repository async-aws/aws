<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a get commit operation.
 */
final class GetCommitInput extends Input
{
    /**
     * The name of the repository to which the commit was made.
     *
     * @required
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The commit ID. Commit IDs are the full SHA ID of the commit.
     *
     * @required
     *
     * @var string|null
     */
    private $commitId;

    /**
     * @param array{
     *   repositoryName?: string,
     *   commitId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->commitId = $input['commitId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string,
     *   commitId?: string,
     *   '@region'?: string|null,
     * }|GetCommitInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCommitId(): ?string
    {
        return $this->commitId;
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
            'Accept' => 'application/json',
            'X-Amz-Target' => 'CodeCommit_20150413.GetCommit',
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

    public function setCommitId(?string $value): self
    {
        $this->commitId = $value;

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
            throw new InvalidArgument(sprintf('Missing parameter "repositoryName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['repositoryName'] = $v;
        if (null === $v = $this->commitId) {
            throw new InvalidArgument(sprintf('Missing parameter "commitId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['commitId'] = $v;

        return $payload;
    }
}
