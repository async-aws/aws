<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a get branch operation.
 */
final class GetBranchInput extends Input
{
    /**
     * The name of the repository that contains the branch for which you want to retrieve information.
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The name of the branch for which you want to retrieve information.
     *
     * @var string|null
     */
    private $branchName;

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   branchName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->branchName = $input['branchName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string|null,
     *   branchName?: string|null,
     *   '@region'?: string|null,
     * }|GetBranchInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBranchName(): ?string
    {
        return $this->branchName;
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
            'X-Amz-Target' => 'CodeCommit_20150413.GetBranch',
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

    public function setBranchName(?string $value): self
    {
        $this->branchName = $value;

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
        if (null !== $v = $this->repositoryName) {
            $payload['repositoryName'] = $v;
        }
        if (null !== $v = $this->branchName) {
            $payload['branchName'] = $v;
        }

        return $payload;
    }
}
