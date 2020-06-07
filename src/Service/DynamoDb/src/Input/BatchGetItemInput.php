<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

final class BatchGetItemInput extends Input
{
    /**
     * A map of one or more table names and, for each table, a map that describes one or more items to retrieve from that
     * table. Each table name can be used only once per `BatchGetItem` request.
     *
     * @required
     *
     * @var array<string, KeysAndAttributes>
     */
    private $RequestItems;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $ReturnConsumedCapacity;

    /**
     * @param array{
     *   RequestItems?: array<string, \AsyncAws\DynamoDb\ValueObject\KeysAndAttributes>,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->RequestItems = [];
        foreach ($input['RequestItems'] ?? [] as $key => $item) {
            $this->RequestItems[$key] = KeysAndAttributes::create($item);
        }
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
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
        return $this->RequestItems;
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->ReturnConsumedCapacity;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, KeysAndAttributes> $value
     */
    public function setRequestItems(array $value): self
    {
        $this->RequestItems = $value;

        return $this;
    }

    /**
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->ReturnConsumedCapacity = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        foreach ($this->RequestItems as $name => $v) {
            $payload['RequestItems'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->ReturnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }

        return $payload;
    }
}
