<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetQueryResultsInput extends Input
{
    /**
     * The unique ID of the query execution.
     *
     * @required
     *
     * @var string|null
     */
    private $queryExecutionId;

    /**
     * A token generated by the Athena service that specifies where to continue pagination if a previous request was
     * truncated. To obtain the next set of pages, pass in the `NextToken` from the response object of the previous page
     * call.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of results (rows) to return in this request.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * @param array{
     *   QueryExecutionId?: string,
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryExecutionId = $input['QueryExecutionId'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueryExecutionId?: string,
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   '@region'?: string|null,
     * }|GetQueryResultsInput $input
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

    public function getQueryExecutionId(): ?string
    {
        return $this->queryExecutionId;
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
            'X-Amz-Target' => 'AmazonAthena.GetQueryResults',
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

    public function setQueryExecutionId(?string $value): self
    {
        $this->queryExecutionId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryExecutionId) {
            throw new InvalidArgument(sprintf('Missing parameter "QueryExecutionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryExecutionId'] = $v;
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }

        return $payload;
    }
}
