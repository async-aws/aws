<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListApiKeysRequest extends Input
{
    /**
     * The API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * An identifier that was returned from the previous call to this operation, which you can use to return the next set of
     * items in the list.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of results that you want the request to return.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * @param array{
     *   apiId?: string,
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   apiId?: string,
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   '@region'?: string|null,
     * }|ListApiKeysRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
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

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(\sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/apikeys';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApiId(?string $value): self
    {
        $this->apiId = $value;

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
}
