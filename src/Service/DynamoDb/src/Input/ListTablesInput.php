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
    private $ExclusiveStartTableName;

    /**
     * A maximum number of table names to return. If this parameter is not specified, the limit is 100.
     *
     * @var int|null
     */
    private $Limit;

    /**
     * @param array{
     *   ExclusiveStartTableName?: string,
     *   Limit?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ExclusiveStartTableName = $input['ExclusiveStartTableName'] ?? null;
        $this->Limit = $input['Limit'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExclusiveStartTableName(): ?string
    {
        return $this->ExclusiveStartTableName;
    }

    public function getLimit(): ?int
    {
        return $this->Limit;
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

    public function setExclusiveStartTableName(?string $value): self
    {
        $this->ExclusiveStartTableName = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->Limit = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ExclusiveStartTableName) {
            $payload['ExclusiveStartTableName'] = $v;
        }
        if (null !== $v = $this->Limit) {
            $payload['Limit'] = $v;
        }

        return $payload;
    }
}
