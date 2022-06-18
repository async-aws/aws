<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\ValueObject\ConditionCheck;
use AsyncAws\DynamoDb\ValueObject\Delete;
use AsyncAws\DynamoDb\ValueObject\Put;
use AsyncAws\DynamoDb\ValueObject\TransactWriteItem;
use AsyncAws\DynamoDb\ValueObject\Update;

final class TransactWriteItemsInput extends Input
{
    /**
     * An ordered array of up to 25 `TransactWriteItem` objects, each of which contains a `ConditionCheck`, `Put`, `Update`,
     * or `Delete` object. These can operate on items in different tables, but the tables must reside in the same Amazon Web
     * Services account and Region, and no two of them can operate on the same item.
     *
     * @required
     *
     * @var TransactWriteItem[]|null
     */
    private $transactItems;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
     * item collections (if any), that were modified during the operation and are returned in the response. If set to `NONE`
     * (the default), no statistics are returned.
     *
     * @var ReturnItemCollectionMetrics::*|null
     */
    private $returnItemCollectionMetrics;

    /**
     * Providing a `ClientRequestToken` makes the call to `TransactWriteItems` idempotent, meaning that multiple identical
     * calls have the same effect as one single call.
     *
     * @var string|null
     */
    private $clientRequestToken;

    /**
     * @param array{
     *   TransactItems?: TransactWriteItem[],
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   ClientRequestToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->transactItems = isset($input['TransactItems']) ? array_map([TransactWriteItem::class, 'create'], $input['TransactItems']) : null;
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->returnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @return ReturnItemCollectionMetrics::*|null
     */
    public function getReturnItemCollectionMetrics(): ?string
    {
        return $this->returnItemCollectionMetrics;
    }

    /**
     * @return TransactWriteItem[]
     */
    public function getTransactItems(): array
    {
        return $this->transactItems ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.TransactWriteItems',
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

    public function setClientRequestToken(?string $value): self
    {
        $this->clientRequestToken = $value;

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

    /**
     * @param ReturnItemCollectionMetrics::*|null $value
     */
    public function setReturnItemCollectionMetrics(?string $value): self
    {
        $this->returnItemCollectionMetrics = $value;

        return $this;
    }

    /**
     * @param TransactWriteItem[] $value
     */
    public function setTransactItems(array $value): self
    {
        $this->transactItems = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->transactItems) {
            throw new InvalidArgument(sprintf('Missing parameter "TransactItems" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['TransactItems'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['TransactItems'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->returnItemCollectionMetrics) {
            if (!ReturnItemCollectionMetrics::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnItemCollectionMetrics" for "%s". The value "%s" is not a valid "ReturnItemCollectionMetrics".', __CLASS__, $v));
            }
            $payload['ReturnItemCollectionMetrics'] = $v;
        }
        if (null === $v = $this->clientRequestToken) {
            $v = uuid_create(\UUID_TYPE_RANDOM);
        }
        $payload['ClientRequestToken'] = $v;

        return $payload;
    }
}
