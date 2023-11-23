<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListNamedQueriesInput extends Input
{
    /**
     * A token generated by the Athena service that specifies where to continue pagination if a previous request was
     * truncated. To obtain the next set of pages, pass in the `NextToken` from the response object of the previous page
     * call.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of queries to return in this request.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The name of the workgroup from which the named queries are being returned. If a workgroup is not specified, the saved
     * queries for the primary workgroup are returned.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * @param array{
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   WorkGroup?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->workGroup = $input['WorkGroup'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   WorkGroup?: null|string,
     *   '@region'?: string|null,
     * }|ListNamedQueriesInput $input
     */
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

    public function getWorkGroup(): ?string
    {
        return $this->workGroup;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'Accept' => 'application/json',
            'X-Amz-Target' => 'AmazonAthena.ListNamedQueries',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
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

    public function setWorkGroup(?string $value): self
    {
        $this->workGroup = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->workGroup) {
            $payload['WorkGroup'] = $v;
        }

        return $payload;
    }
}
