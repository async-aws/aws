<?php

namespace AsyncAws\TimestreamQuery\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CancelQueryRequest extends Input
{
    /**
     * The ID of the query that needs to be cancelled. `QueryID` is returned as part of the query result.
     *
     * @required
     *
     * @var string|null
     */
    private $queryId;

    /**
     * @param array{
     *   QueryId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryId = $input['QueryId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueryId(): ?string
    {
        return $this->queryId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'Timestream_20181101.CancelQuery',
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

    public function setQueryId(?string $value): self
    {
        $this->queryId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queryId) {
            throw new InvalidArgument(sprintf('Missing parameter "QueryId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueryId'] = $v;

        return $payload;
    }
}
