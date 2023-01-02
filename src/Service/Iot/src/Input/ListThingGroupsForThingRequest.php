<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListThingGroupsForThingRequest extends Input
{
    /**
     * The thing name.
     *
     * @required
     *
     * @var string|null
     */
    private $thingName;

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
     * @param array{
     *   thingName?: string,
     *   nextToken?: string,
     *   maxResults?: int,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
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

    public function getThingName(): ?string
    {
        return $this->thingName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];
        if (null !== $this->nextToken) {
            $query['nextToken'] = $this->nextToken;
        }
        if (null !== $this->maxResults) {
            $query['maxResults'] = (string) $this->maxResults;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingName) {
            throw new InvalidArgument(sprintf('Missing parameter "thingName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingName'] = $v;
        $uriString = '/things/' . rawurlencode($uri['thingName']) . '/thing-groups';

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

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setThingName(?string $value): self
    {
        $this->thingName = $value;

        return $this;
    }
}
