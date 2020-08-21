<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;

final class BatchWriteItemInput extends Input
{
    /**
     * A map of one or more table names and, for each table, a list of operations to be performed (`DeleteRequest` or
     * `PutRequest`). Each element in the map consists of the following:.
     *
     * @required
     *
     * @var array<string, WriteRequest[]>|null
     */
    private $RequestItems;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $ReturnConsumedCapacity;

    /**
     * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
     * item collections, if any, that were modified during the operation are returned in the response. If set to `NONE` (the
     * default), no statistics are returned.
     *
     * @var null|ReturnItemCollectionMetrics::*
     */
    private $ReturnItemCollectionMetrics;

    /**
     * @param array{
     *   RequestItems?: array<string, array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->RequestItems = [];
        foreach ($input['RequestItems'] ?? [] as $key => $item) {
            $this->RequestItems[$key] = array_map([WriteRequest::class, 'create'], $item);
        }
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->ReturnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, WriteRequest[]>
     */
    public function getRequestItems(): array
    {
        return $this->RequestItems ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->ReturnConsumedCapacity;
    }

    /**
     * @return ReturnItemCollectionMetrics::*|null
     */
    public function getReturnItemCollectionMetrics(): ?string
    {
        return $this->ReturnItemCollectionMetrics;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.BatchWriteItem',
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
     * @param array<string, WriteRequest[]> $value
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

    /**
     * @param ReturnItemCollectionMetrics::*|null $value
     */
    public function setReturnItemCollectionMetrics(?string $value): self
    {
        $this->ReturnItemCollectionMetrics = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->RequestItems) {
            throw new InvalidArgument(sprintf('Missing parameter "RequestItems" for "%s". The value cannot be null.', __CLASS__));
        }

        foreach ($v as $name => $v) {
            $index = -1;
            $payload['RequestItems'][$name] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['RequestItems'][$name][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->ReturnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->ReturnItemCollectionMetrics) {
            if (!ReturnItemCollectionMetrics::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnItemCollectionMetrics" for "%s". The value "%s" is not a valid "ReturnItemCollectionMetrics".', __CLASS__, $v));
            }
            $payload['ReturnItemCollectionMetrics'] = $v;
        }

        return $payload;
    }
}
