<?php

namespace AsyncAws\TimestreamQuery\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class QueryRequest extends Input
{
    /**
     * The query to be run by Timestream.
     *
     * @required
     *
     * @var string|null
     */
    private $queryString;

    /**
     * Unique, case-sensitive string of up to 64 ASCII characters specified when a `Query` request is made. Providing a
     * `ClientToken` makes the call to `Query`*idempotent*. This means that running the same query repeatedly will produce
     * the same result. In other words, making multiple identical `Query` requests has the same effect as making a single
     * request. When using `ClientToken` in a query, note the following:.
     *
     * @var string|null
     */
    private $clientToken;

    /**
     * A pagination token used to return a set of results. When the `Query` API is invoked using `NextToken`, that
     * particular invocation is assumed to be a subsequent invocation of a prior call to `Query`, and a result set is
     * returned. However, if the `Query` invocation only contains the `ClientToken`, that invocation of `Query` is assumed
     * to be a new query run.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The total number of rows to be returned in the `Query` output. The initial run of `Query` with a `MaxRows` value
     * specified will return the result set of the query in two cases:.
     *
     * @var int|null
     */
    private $maxRows;

    /**
     * @param array{
     *   QueryString?: string,
     *   ClientToken?: string,
     *   NextToken?: string,
     *   MaxRows?: int,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryString = $input['QueryString'] ?? null;
        $this->clientToken = $input['ClientToken'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxRows = $input['MaxRows'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientToken(): ?string
    {
        return $this->clientToken;
    }

    public function getMaxRows(): ?int
    {
        return $this->maxRows;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getQueryString(): ?string
    {
        return $this->queryString;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'Timestream_20181101.Query',
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

    public function setClientToken(?string $value): self
    {
        $this->clientToken = $value;

        return $this;
    }

    public function setMaxRows(?int $value): self
    {
        $this->maxRows = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setQueryString(?string $value): self
    {
        $this->queryString = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryString) {
            throw new InvalidArgument(sprintf('Missing parameter "QueryString" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryString'] = $v;
        if (null === $v = $this->clientToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientToken'] = $v;
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->maxRows) {
            $payload['MaxRows'] = $v;
        }

        return $payload;
    }
}
