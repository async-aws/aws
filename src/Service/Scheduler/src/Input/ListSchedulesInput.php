<?php

namespace AsyncAws\Scheduler\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Scheduler\Enum\ScheduleState;

final class ListSchedulesInput extends Input
{
    /**
     * If specified, only lists the schedules whose associated schedule group matches the given filter.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * If specified, limits the number of results returned by this operation. The operation also returns a `NextToken` which
     * you can use in a subsequent operation to retrieve the next set of results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Schedule name prefix to return the filtered list of resources.
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
     * If specified, only lists the schedules whose current state matches the given filter.
     *
     * @var ScheduleState::*|null
     */
    private $state;

    /**
     * @param array{
     *   GroupName?: string|null,
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   State?: ScheduleState::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->groupName = $input['GroupName'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->namePrefix = $input['NamePrefix'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->state = $input['State'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   GroupName?: string|null,
     *   MaxResults?: int|null,
     *   NamePrefix?: string|null,
     *   NextToken?: string|null,
     *   State?: ScheduleState::*|null,
     *   '@region'?: string|null,
     * }|ListSchedulesInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
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
     * @return ScheduleState::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
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
        if (null !== $this->groupName) {
            $query['ScheduleGroup'] = $this->groupName;
        }
        if (null !== $this->maxResults) {
            $query['MaxResults'] = (string) $this->maxResults;
        }
        if (null !== $this->namePrefix) {
            $query['NamePrefix'] = $this->namePrefix;
        }
        if (null !== $this->nextToken) {
            $query['NextToken'] = $this->nextToken;
        }
        if (null !== $this->state) {
            if (!ScheduleState::exists($this->state)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "State" for "%s". The value "%s" is not a valid "ScheduleState".', __CLASS__, $this->state));
            }
            $query['State'] = $this->state;
        }

        // Prepare URI
        $uriString = '/schedules';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setGroupName(?string $value): self
    {
        $this->groupName = $value;

        return $this;
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

    /**
     * @param ScheduleState::*|null $value
     */
    public function setState(?string $value): self
    {
        $this->state = $value;

        return $this;
    }
}
