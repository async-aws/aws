<?php

namespace AsyncAws\Ssm\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Ssm\ValueObject\ParameterStringFilter;

final class GetParametersByPathRequest extends Input
{
    /**
     * The hierarchy for the parameter. Hierarchies start with a forward slash (/). The hierarchy is the parameter name
     * except the last part of the parameter. For the API call to succeed, the last part of the parameter name can't be in
     * the path. A parameter name hierarchy can have a maximum of 15 levels. Here is an example of a hierarchy:
     * `/Finance/Prod/IAD/WinServ2016/license33 `.
     *
     * @required
     *
     * @var string|null
     */
    private $path;

    /**
     * Retrieve all parameters within a hierarchy.
     *
     * ! If a user has access to a path, then the user can access all levels of that path. For example, if a user has
     * ! permission to access path `/a`, then the user can also access `/a/b`. Even if a user has explicitly been denied
     * ! access in IAM for parameter `/a/b`, they can still call the GetParametersByPath API operation recursively for `/a`
     * ! and view `/a/b`.
     *
     * @var bool|null
     */
    private $recursive;

    /**
     * Filters to limit the request results.
     *
     * > The following `Key` values are supported for `GetParametersByPath`: `Type`, `KeyId`, and `Label`.
     * >
     * > The following `Key` values aren't supported for `GetParametersByPath`: `tag`, `DataType`, `Name`, `Path`, and
     * > `Tier`.
     *
     * @var ParameterStringFilter[]|null
     */
    private $parameterFilters;

    /**
     * Retrieve all parameters in a hierarchy with their value decrypted.
     *
     * @var bool|null
     */
    private $withDecryption;

    /**
     * The maximum number of items to return for this call. The call also returns a token that you can specify in a
     * subsequent call to get the next set of results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * A token to start the list. Use this token to get the next set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   Path?: string,
     *   Recursive?: bool,
     *   ParameterFilters?: ParameterStringFilter[],
     *   WithDecryption?: bool,
     *   MaxResults?: int,
     *   NextToken?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->path = $input['Path'] ?? null;
        $this->recursive = $input['Recursive'] ?? null;
        $this->parameterFilters = isset($input['ParameterFilters']) ? array_map([ParameterStringFilter::class, 'create'], $input['ParameterFilters']) : null;
        $this->withDecryption = $input['WithDecryption'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return ParameterStringFilter[]
     */
    public function getParameterFilters(): array
    {
        return $this->parameterFilters ?? [];
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getRecursive(): ?bool
    {
        return $this->recursive;
    }

    public function getWithDecryption(): ?bool
    {
        return $this->withDecryption;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AmazonSSM.GetParametersByPath',
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

    /**
     * @param ParameterStringFilter[] $value
     */
    public function setParameterFilters(array $value): self
    {
        $this->parameterFilters = $value;

        return $this;
    }

    public function setPath(?string $value): self
    {
        $this->path = $value;

        return $this;
    }

    public function setRecursive(?bool $value): self
    {
        $this->recursive = $value;

        return $this;
    }

    public function setWithDecryption(?bool $value): self
    {
        $this->withDecryption = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->path) {
            throw new InvalidArgument(sprintf('Missing parameter "Path" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Path'] = $v;
        if (null !== $v = $this->recursive) {
            $payload['Recursive'] = (bool) $v;
        }
        if (null !== $v = $this->parameterFilters) {
            $index = -1;
            $payload['ParameterFilters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ParameterFilters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->withDecryption) {
            $payload['WithDecryption'] = (bool) $v;
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
