<?php

namespace AsyncAws\Rekognition\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListCollectionsRequest extends Input
{
    /**
     * Pagination token from the previous response.
     *
     * @var string|null
     */
    private $NextToken;

    /**
     * Maximum number of collection IDs to return.
     *
     * @var int|null
     */
    private $MaxResults;

    /**
     * @param array{
     *   NextToken?: string,
     *   MaxResults?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->NextToken = $input['NextToken'] ?? null;
        $this->MaxResults = $input['MaxResults'] ?? null;
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
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'RekognitionService.ListCollections',
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

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->NextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->MaxResults) {
            $payload['MaxResults'] = $v;
        }

        return $payload;
    }
}
