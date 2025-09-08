<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListThingGroupsRequest extends Input
{
    /**
     * To retrieve the next set of results, the `nextToken` value from a previous response; otherwise **null** to receive
     * the first set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of results to return at one time.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * A filter that limits the results to those with the specified parent group.
     *
     * @var string|null
     */
    private $parentGroup;

    /**
     * A filter that limits the results to those with the specified name prefix.
     *
     * @var string|null
     */
    private $namePrefixFilter;

    /**
     * If true, return child groups as well.
     *
     * @var bool|null
     */
    private $recursive;

    /**
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   parentGroup?: string|null,
     *   namePrefixFilter?: string|null,
     *   recursive?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->parentGroup = $input['parentGroup'] ?? null;
        $this->namePrefixFilter = $input['namePrefixFilter'] ?? null;
        $this->recursive = $input['recursive'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   parentGroup?: string|null,
     *   namePrefixFilter?: string|null,
     *   recursive?: bool|null,
     *   '@region'?: string|null,
     * }|ListThingGroupsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNamePrefixFilter(): ?string
    {
        return $this->namePrefixFilter;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getParentGroup(): ?string
    {
        return $this->parentGroup;
    }

    public function getRecursive(): ?bool
    {
        return $this->recursive;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->nextToken) {
            $query['nextToken'] = $this->nextToken;
        }
        if (null !== $this->maxResults) {
            $query['maxResults'] = (string) $this->maxResults;
        }
        if (null !== $this->parentGroup) {
            $query['parentGroup'] = $this->parentGroup;
        }
        if (null !== $this->namePrefixFilter) {
            $query['namePrefixFilter'] = $this->namePrefixFilter;
        }
        if (null !== $this->recursive) {
            $query['recursive'] = $this->recursive ? 'true' : 'false';
        }

        // Prepare URI
        $uriString = '/thing-groups';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNamePrefixFilter(?string $value): self
    {
        $this->namePrefixFilter = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setParentGroup(?string $value): self
    {
        $this->parentGroup = $value;

        return $this;
    }

    public function setRecursive(?bool $value): self
    {
        $this->recursive = $value;

        return $this;
    }
}
