<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a `ListTables` operation.
 */
final class ListTablesInput extends Input
{
    /**
     * The first table name that this operation will evaluate. Use the value that was returned for `LastEvaluatedTableName`
     * in a previous operation, so that you can obtain the next page of results.
     *
     * @var string|null
     */
    private $exclusiveStartTableName;

    /**
     * A maximum number of table names to return. If this parameter is not specified, the limit is 100.
     *
     * @var int|null
     */
    private $limit;

    /**
     * @param array{
     *   ExclusiveStartTableName?: string|null,
     *   Limit?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->exclusiveStartTableName = $input['ExclusiveStartTableName'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ExclusiveStartTableName?: string|null,
     *   Limit?: int|null,
     *   '@region'?: string|null,
     * }|ListTablesInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExclusiveStartTableName(): ?string
    {
        return $this->exclusiveStartTableName;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.ListTables',
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

    public function setExclusiveStartTableName(?string $value): self
    {
        $this->exclusiveStartTableName = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->exclusiveStartTableName) {
            $payload['ExclusiveStartTableName'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }

        return $payload;
    }
}
