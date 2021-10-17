<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

/**
 * Represents the input of a `BatchGetItem` operation.
 */
final class BatchGetItemInput extends Input
{
    /**
     * A map of one or more table names and, for each table, a map that describes one or more items to retrieve from that
     * table. Each table name can be used only once per `BatchGetItem` request.
     *
     * @required
     *
     * @var array<string, KeysAndAttributes>|null
     */
    private $requestItems;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $returnConsumedCapacity;

    /**
     * @param array{
     *   RequestItems?: array<string, KeysAndAttributes>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        if (isset($input['RequestItems'])) {
            $this->requestItems = [];
            foreach ($input['RequestItems'] as $key => $item) {
                $this->requestItems[$key] = KeysAndAttributes::create($item);
            }
        }
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, KeysAndAttributes>
     */
    public function getRequestItems(): array
    {
        return $this->requestItems ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.BatchGetItem',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : \json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, KeysAndAttributes> $value
     */
    public function setRequestItems(array $value): self
    {
        $this->requestItems = $value;

        return $this;
    }

    /**
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->returnConsumedCapacity = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->requestItems) {
            throw new InvalidArgument(sprintf('Missing parameter "RequestItems" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['RequestItems'] = new \stdClass();
        } else {
            $payload['RequestItems'] = [];
            foreach ($v as $name => $mv) {
                $payload['RequestItems'][$name] = $mv->requestBody();
            }
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }

        return $payload;
    }
}
