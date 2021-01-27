<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents an operation to perform - either `DeleteItem` or `PutItem`. You can only request one of these operations,
 * not both, in a single `WriteRequest`. If you do need to perform both of these operations, you need to provide two
 * separate `WriteRequest` objects.
 */
final class WriteRequest
{
    /**
     * A request to perform a `PutItem` operation.
     */
    private $putRequest;

    /**
     * A request to perform a `DeleteItem` operation.
     */
    private $deleteRequest;

    /**
     * @param array{
     *   PutRequest?: null|PutRequest|array,
     *   DeleteRequest?: null|DeleteRequest|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->putRequest = isset($input['PutRequest']) ? PutRequest::create($input['PutRequest']) : null;
        $this->deleteRequest = isset($input['DeleteRequest']) ? DeleteRequest::create($input['DeleteRequest']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeleteRequest(): ?DeleteRequest
    {
        return $this->deleteRequest;
    }

    public function getPutRequest(): ?PutRequest
    {
        return $this->putRequest;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->putRequest) {
            $payload['PutRequest'] = $v->requestBody();
        }
        if (null !== $v = $this->deleteRequest) {
            $payload['DeleteRequest'] = $v->requestBody();
        }

        return $payload;
    }
}
