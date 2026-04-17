<?php

namespace AsyncAws\ImageBuilder\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\ImageBuilder\Enum\Ownership;
use AsyncAws\ImageBuilder\ValueObject\Filter;

final class ListImagesRequest extends Input
{
    /**
     * The owner defines which images you want to list. By default, this request will only show images owned by your
     * account. You can use this field to specify if you want to view images owned by yourself, by Amazon, or those images
     * that have been shared with you by other customers.
     *
     * @var Ownership::*|null
     */
    private $owner;

    /**
     * Use the following filters to streamline results:
     *
     * - `name`
     * - `osVersion`
     * - `platform`
     * - `type`
     * - `version`
     *
     * @var Filter[]|null
     */
    private $filters;

    /**
     * Requests a list of images with a specific recipe name.
     *
     * @var bool|null
     */
    private $byName;

    /**
     * Specify the maximum number of items to return in a request.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * A token to specify where to start paginating. This is the nextToken from a previously truncated response.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Includes deprecated images in the response list.
     *
     * @var bool|null
     */
    private $includeDeprecated;

    /**
     * @param array{
     *   owner?: Ownership::*|null,
     *   filters?: array<Filter|array>|null,
     *   byName?: bool|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   includeDeprecated?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->owner = $input['owner'] ?? null;
        $this->filters = isset($input['filters']) ? array_map([Filter::class, 'create'], $input['filters']) : null;
        $this->byName = $input['byName'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->includeDeprecated = $input['includeDeprecated'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   owner?: Ownership::*|null,
     *   filters?: array<Filter|array>|null,
     *   byName?: bool|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   includeDeprecated?: bool|null,
     *   '@region'?: string|null,
     * }|ListImagesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getByName(): ?bool
    {
        return $this->byName;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    public function getIncludeDeprecated(): ?bool
    {
        return $this->includeDeprecated;
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
     * @return Ownership::*|null
     */
    public function getOwner(): ?string
    {
        return $this->owner;
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

        // Prepare URI
        $uriString = '/ListImages';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setByName(?bool $value): self
    {
        $this->byName = $value;

        return $this;
    }

    /**
     * @param Filter[] $value
     */
    public function setFilters(array $value): self
    {
        $this->filters = $value;

        return $this;
    }

    public function setIncludeDeprecated(?bool $value): self
    {
        $this->includeDeprecated = $value;

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

    /**
     * @param Ownership::*|null $value
     */
    public function setOwner(?string $value): self
    {
        $this->owner = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->owner) {
            if (!Ownership::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "owner" for "%s". The value "%s" is not a valid "Ownership".', __CLASS__, $v));
            }
            $payload['owner'] = $v;
        }
        if (null !== $v = $this->filters) {
            $index = -1;
            $payload['filters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['filters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->byName) {
            $payload['byName'] = (bool) $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->includeDeprecated) {
            $payload['includeDeprecated'] = (bool) $v;
        }

        return $payload;
    }
}
