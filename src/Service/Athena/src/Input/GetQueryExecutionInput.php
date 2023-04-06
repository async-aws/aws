<?php

namespace AsyncAws\Athena\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetQueryExecutionInput extends Input
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
     * @param array{
     *   QueryExecutionId?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queryExecutionId = $input['QueryExecutionId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
            'X-Amz-Target' => 'AmazonAthena.GetQueryExecution',
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

        return $payload;
    }
}
