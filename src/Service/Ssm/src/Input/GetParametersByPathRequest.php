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
     * The hierarchy for the parameter. Hierarchies start with a forward slash (/) and end with the parameter name. A
     * parameter name hierarchy can have a maximum of 15 levels. Here is an example of a hierarchy:
     * `/Finance/Prod/IAD/WinServ2016/license33`.
     *
     * @required
     *
     * @var string|null
     */
    private $Path;

    /**
     * Retrieve all parameters within a hierarchy.
     *
     * @var bool|null
     */
    private $Recursive;

    /**
     * Filters to limit the request results.
     *
     * @var ParameterStringFilter[]|null
     */
    private $ParameterFilters;

    /**
     * Retrieve all parameters in a hierarchy with their value decrypted.
     *
     * @var bool|null
     */
    private $WithDecryption;

    /**
     * The maximum number of items to return for this call. The call also returns a token that you can specify in a
     * subsequent call to get the next set of results.
     *
     * @var int|null
     */
    private $MaxResults;

    /**
     * A token to start the list. Use this token to get the next set of results.
     *
     * @var string|null
     */
    private $NextToken;

    /**
     * @param array{
     *   Path?: string,
     *   Recursive?: bool,
     *   ParameterFilters?: \AsyncAws\Ssm\ValueObject\ParameterStringFilter[],
     *   WithDecryption?: bool,
     *   MaxResults?: int,
     *   NextToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Path = $input['Path'] ?? null;
        $this->Recursive = $input['Recursive'] ?? null;
        $this->ParameterFilters = isset($input['ParameterFilters']) ? array_map([ParameterStringFilter::class, 'create'], $input['ParameterFilters']) : null;
        $this->WithDecryption = $input['WithDecryption'] ?? null;
        $this->MaxResults = $input['MaxResults'] ?? null;
        $this->NextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->MaxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->NextToken;
    }

    /**
     * @return ParameterStringFilter[]
     */
    public function getParameterFilters(): array
    {
        return $this->ParameterFilters ?? [];
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function getRecursive(): ?bool
    {
        return $this->Recursive;
    }

    public function getWithDecryption(): ?bool
    {
        return $this->WithDecryption;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMaxResults(?int $value): self
    {
        $this->MaxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->NextToken = $value;

        return $this;
    }

    /**
     * @param ParameterStringFilter[] $value
     */
    public function setParameterFilters(array $value): self
    {
        $this->ParameterFilters = $value;

        return $this;
    }

    public function setPath(?string $value): self
    {
        $this->Path = $value;

        return $this;
    }

    public function setRecursive(?bool $value): self
    {
        $this->Recursive = $value;

        return $this;
    }

    public function setWithDecryption(?bool $value): self
    {
        $this->WithDecryption = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Path) {
            throw new InvalidArgument(sprintf('Missing parameter "Path" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Path'] = $v;
        if (null !== $v = $this->Recursive) {
            $payload['Recursive'] = (bool) $v;
        }
        if (null !== $v = $this->ParameterFilters) {
            $index = -1;
            $payload['ParameterFilters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ParameterFilters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->WithDecryption) {
            $payload['WithDecryption'] = (bool) $v;
        }
        if (null !== $v = $this->MaxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->NextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
