<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListScheduleGroupsInput extends Input
{
    /**
     * If specified, limits the number of results returned by this operation. The operation also returns a `NextToken` which
     * you can use in a subsequent operation to retrieve the next set of results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The name prefix that you can use to return a filtered list of your schedule groups.
     *
     * @var string|null
     */
    private $namePrefix;

    /**
     * The token returned by a previous call to retrieve the next set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->namePrefix = $input['NamePrefix'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   '@region'?: string|null,
     * }|ListScheduleGroupsInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
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
        if (null !== $this->maxResults) {
            $query['MaxResults'] = (string) $this->maxResults;
        }
        if (null !== $this->namePrefix) {
            $query['NamePrefix'] = $this->namePrefix;
        }
        if (null !== $this->nextToken) {
            $query['NextToken'] = $this->nextToken;
        }

        // Prepare URI
        $uriString = '/schedule-groups';

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

    public function setNamePrefix(?string $value): self
    {
        $this->namePrefix = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }
}
