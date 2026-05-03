<?php

namespace AsyncAws\ImageBuilder\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\ImageBuilder\ValueObject\Filter;

final class ListImageBuildVersionsRequest extends Input
{
    /**
     * The Amazon Resource Name (ARN) of the image whose build versions you want to retrieve.
     *
     * @var string|null
     */
    private $imageVersionArn;

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
     * @param array{
     *   imageVersionArn?: string|null,
     *   filters?: array<Filter|array>|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->imageVersionArn = $input['imageVersionArn'] ?? null;
        $this->filters = isset($input['filters']) ? array_map([Filter::class, 'create'], $input['filters']) : null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   imageVersionArn?: string|null,
     *   filters?: array<Filter|array>|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     *   '@responseBuffer'?: bool,
     * }|ListImageBuildVersionsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    public function getImageVersionArn(): ?string
    {
        return $this->imageVersionArn;
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

        // Prepare URI
        $uriString = '/ListImageBuildVersions';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param Filter[] $value
     */
    public function setFilters(array $value): self
    {
        $this->filters = $value;

        return $this;
    }

    public function setImageVersionArn(?string $value): self
    {
        $this->imageVersionArn = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->imageVersionArn) {
            $payload['imageVersionArn'] = $v;
        }
        if (null !== $v = $this->filters) {
            $index = -1;
            $payload['filters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['filters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }

        return $payload;
    }
}
