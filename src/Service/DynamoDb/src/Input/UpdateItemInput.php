<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\Enum\ReturnValue;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\AttributeValueUpdate;
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;

final class UpdateItemInput extends Input
{
    /**
     * The name of the table containing the item to update.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * The primary key of the item to be updated. Each element consists of an attribute name and a value for that attribute.
     *
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $Key;

    /**
     * This is a legacy parameter. Use `UpdateExpression` instead. For more information, see AttributeUpdates in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributeUpdates.html
     *
     * @var array<string, AttributeValueUpdate>|null
     */
    private $AttributeUpdates;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see Expected in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.Expected.html
     *
     * @var array<string, ExpectedAttributeValue>|null
     */
    private $Expected;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var null|ConditionalOperator::*
     */
    private $ConditionalOperator;

    /**
     * Use `ReturnValues` if you want to get the item attributes as they appear before or after they are updated. For
     * `UpdateItem`, the valid values are:.
     *
     * @var null|ReturnValue::*
     */
    private $ReturnValues;

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
     * An expression that defines one or more attributes to be updated, the action to be performed on them, and new values
     * for them.
     *
     * @var string|null
     */
    private $UpdateExpression;

    /**
     * A condition that must be satisfied in order for a conditional update to succeed.
     *
     * @var string|null
     */
    private $ConditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. The following are some use cases for using
     * `ExpressionAttributeNames`:.
     *
     * @var array<string, string>|null
     */
    private $ExpressionAttributeNames;

    /**
     * One or more values that can be substituted in an expression.
     *
     * @var array<string, AttributeValue>|null
     */
    private $ExpressionAttributeValues;

    /**
     * @param array{
     *   TableName?: string,
     *   Key?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   AttributeUpdates?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValueUpdate>,
     *   Expected?: array<string, \AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue>,
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ReturnValues?: \AsyncAws\DynamoDb\Enum\ReturnValue::*,
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: \AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics::*,
     *   UpdateExpression?: string,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->TableName = $input['TableName'] ?? null;

        $this->Key = [];
        foreach ($input['Key'] ?? [] as $key => $item) {
            $this->Key[$key] = AttributeValue::create($item);
        }

        $this->AttributeUpdates = [];
        foreach ($input['AttributeUpdates'] ?? [] as $key => $item) {
            $this->AttributeUpdates[$key] = AttributeValueUpdate::create($item);
        }

        $this->Expected = [];
        foreach ($input['Expected'] ?? [] as $key => $item) {
            $this->Expected[$key] = ExpectedAttributeValue::create($item);
        }
        $this->ConditionalOperator = $input['ConditionalOperator'] ?? null;
        $this->ReturnValues = $input['ReturnValues'] ?? null;
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->ReturnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        $this->UpdateExpression = $input['UpdateExpression'] ?? null;
        $this->ConditionExpression = $input['ConditionExpression'] ?? null;
        $this->ExpressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;

        $this->ExpressionAttributeValues = [];
        foreach ($input['ExpressionAttributeValues'] ?? [] as $key => $item) {
            $this->ExpressionAttributeValues[$key] = AttributeValue::create($item);
        }
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValueUpdate>
     */
    public function getAttributeUpdates(): array
    {
        return $this->AttributeUpdates ?? [];
    }

    public function getConditionExpression(): ?string
    {
        return $this->ConditionExpression;
    }

    /**
     * @return ConditionalOperator::*|null
     */
    public function getConditionalOperator(): ?string
    {
        return $this->ConditionalOperator;
    }

    /**
     * @return array<string, ExpectedAttributeValue>
     */
    public function getExpected(): array
    {
        return $this->Expected ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getExpressionAttributeNames(): array
    {
        return $this->ExpressionAttributeNames ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getExpressionAttributeValues(): array
    {
        return $this->ExpressionAttributeValues ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->Key ?? [];
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
     * @return ReturnValue::*|null
     */
    public function getReturnValues(): ?string
    {
        return $this->ReturnValues;
    }

    public function getTableName(): ?string
    {
        return $this->TableName;
    }

    public function getUpdateExpression(): ?string
    {
        return $this->UpdateExpression;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.UpdateItem',
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
     * @param array<string, AttributeValueUpdate> $value
     */
    public function setAttributeUpdates(array $value): self
    {
        $this->AttributeUpdates = $value;

        return $this;
    }

    public function setConditionExpression(?string $value): self
    {
        $this->ConditionExpression = $value;

        return $this;
    }

    /**
     * @param ConditionalOperator::*|null $value
     */
    public function setConditionalOperator(?string $value): self
    {
        $this->ConditionalOperator = $value;

        return $this;
    }

    /**
     * @param array<string, ExpectedAttributeValue> $value
     */
    public function setExpected(array $value): self
    {
        $this->Expected = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setExpressionAttributeNames(array $value): self
    {
        $this->ExpressionAttributeNames = $value;

        return $this;
    }

    /**
     * @param array<string, AttributeValue> $value
     */
    public function setExpressionAttributeValues(array $value): self
    {
        $this->ExpressionAttributeValues = $value;

        return $this;
    }

    /**
     * @param array<string, AttributeValue> $value
     */
    public function setKey(array $value): self
    {
        $this->Key = $value;

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

    /**
     * @param ReturnValue::*|null $value
     */
    public function setReturnValues(?string $value): self
    {
        $this->ReturnValues = $value;

        return $this;
    }

    public function setTableName(?string $value): self
    {
        $this->TableName = $value;

        return $this;
    }

    public function setUpdateExpression(?string $value): self
    {
        $this->UpdateExpression = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->TableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }

        foreach ($v as $name => $v) {
            $payload['Key'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->AttributeUpdates) {
            foreach ($v as $name => $v) {
                $payload['AttributeUpdates'][$name] = $v->requestBody();
            }
        }
        if (null !== $v = $this->Expected) {
            foreach ($v as $name => $v) {
                $payload['Expected'][$name] = $v->requestBody();
            }
        }
        if (null !== $v = $this->ConditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
        }
        if (null !== $v = $this->ReturnValues) {
            if (!ReturnValue::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnValues" for "%s". The value "%s" is not a valid "ReturnValue".', __CLASS__, $v));
            }
            $payload['ReturnValues'] = $v;
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
        if (null !== $v = $this->UpdateExpression) {
            $payload['UpdateExpression'] = $v;
        }
        if (null !== $v = $this->ConditionExpression) {
            $payload['ConditionExpression'] = $v;
        }
        if (null !== $v = $this->ExpressionAttributeNames) {
            foreach ($v as $name => $v) {
                $payload['ExpressionAttributeNames'][$name] = $v;
            }
        }
        if (null !== $v = $this->ExpressionAttributeValues) {
            foreach ($v as $name => $v) {
                $payload['ExpressionAttributeValues'][$name] = $v->requestBody();
            }
        }

        return $payload;
    }
}
