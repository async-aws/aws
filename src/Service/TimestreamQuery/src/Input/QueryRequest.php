<?php

namespace AsyncAws\TimestreamQuery\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\TimestreamQuery\ValueObject\QueryInsights;

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
     * request. When using `ClientToken` in a query, note the following:
     *
     * - If the Query API is instantiated without a `ClientToken`, the Query SDK generates a `ClientToken` on your behalf.
     * - If the `Query` invocation only contains the `ClientToken` but does not include a `NextToken`, that invocation of
     *   `Query` is assumed to be a new query run.
     * - If the invocation contains `NextToken`, that particular invocation is assumed to be a subsequent invocation of a
     *   prior call to the Query API, and a result set is returned.
     * - After 4 hours, any request with the same `ClientToken` is treated as a new request.
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
     * Note the following when using NextToken in a query:
     *
     * - A pagination token can be used for up to five `Query` invocations, OR for a duration of up to 1 hour – whichever
     *   comes first.
     * - Using the same `NextToken` will return the same set of records. To keep paginating through the result set, you must
     *   to use the most recent `nextToken`.
     * - Suppose a `Query` invocation returns two `NextToken` values, `TokenA` and `TokenB`. If `TokenB` is used in a
     *   subsequent `Query` invocation, then `TokenA` is invalidated and cannot be reused.
     * - To request a previous result set from a query after pagination has begun, you must re-invoke the Query API.
     * - The latest `NextToken` should be used to paginate until `null` is returned, at which point a new `NextToken` should
     *   be used.
     * - If the IAM principal of the query initiator and the result reader are not the same and/or the query initiator and
     *   the result reader do not have the same query string in the query requests, the query will fail with an `Invalid
     *   pagination token` error.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The total number of rows to be returned in the `Query` output. The initial run of `Query` with a `MaxRows` value
     * specified will return the result set of the query in two cases:
     *
     * - The size of the result is less than `1MB`.
     * - The number of rows in the result set is less than the value of `maxRows`.
     *
     * Otherwise, the initial invocation of `Query` only returns a `NextToken`, which can then be used in subsequent calls
     * to fetch the result set. To resume pagination, provide the `NextToken` value in the subsequent command.
     *
     * If the row size is large (e.g. a row has many columns), Timestream may return fewer rows to keep the response size
     * from exceeding the 1 MB limit. If `MaxRows` is not provided, Timestream will send the necessary number of rows to
     * meet the 1 MB limit.
     *
     * @var int|null
     */
    private $maxRows;

    /**
     * Encapsulates settings for enabling `QueryInsights`.
     *
     * Enabling `QueryInsights` returns insights and metrics in addition to query results for the query that you executed.
     * You can use `QueryInsights` to tune your query performance.
     *
     * @var QueryInsights|null
     */
    private $queryInsights;

    /**
     * @param array{
     *   QueryString?: string,
     *   ClientToken?: string|null,
     *   NextToken?: string|null,
     *   MaxRows?: int|null,
     *   QueryInsights?: QueryInsights|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryString = $input['QueryString'] ?? null;
        $this->clientToken = $input['ClientToken'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxRows = $input['MaxRows'] ?? null;
        $this->queryInsights = isset($input['QueryInsights']) ? QueryInsights::create($input['QueryInsights']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueryString?: string,
     *   ClientToken?: string|null,
     *   NextToken?: string|null,
     *   MaxRows?: int|null,
     *   QueryInsights?: QueryInsights|array|null,
     *   '@region'?: string|null,
     * }|QueryRequest $input
     */
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

    public function getQueryInsights(): ?QueryInsights
    {
        return $this->queryInsights;
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
            'Accept' => 'application/json',
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

    public function setQueryInsights(?QueryInsights $value): self
    {
        $this->queryInsights = $value;

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
            throw new InvalidArgument(\sprintf('Missing parameter "QueryString" for "%s". The value cannot be null.', __CLASS__));
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
        if (null !== $v = $this->queryInsights) {
            $payload['QueryInsights'] = $v->requestBody();
        }

        return $payload;
    }
}
