<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the ListThingTypes operation.
 */
final class ListThingTypesRequest extends Input
{
    /**
     * To retrieve the next set of results, the `nextToken` value from a previous response; otherwise **null** to receive
     * the first set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of results to return in this operation.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The name of the thing type.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * @param array{
     *   nextToken?: string,
     *   maxResults?: int,
     *   thingTypeName?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->thingTypeName = $input['thingTypeName'] ?? null;
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

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
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
        if (null !== $this->thingTypeName) {
            $query['thingTypeName'] = $this->thingTypeName;
        }

        // Prepare URI
        $uriString = '/thing-types';

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

    public function setThingTypeName(?string $value): self
    {
        $this->thingTypeName = $value;

        return $this;
    }
}
