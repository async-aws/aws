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
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;

/**
 * Represents the input of a `PutItem` operation.
 */
final class PutItemInput extends Input
{
    /**
     * The name of the table to contain the item.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * A map of attribute name/value pairs, one for each attribute. Only the primary key attributes are required; you can
     * optionally provide other attribute name-value pairs for the item.
     *
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $Item;

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
     * Use `ReturnValues` if you want to get the item attributes as they appeared before they were updated with the
     * `PutItem` request. For `PutItem`, the valid values are:.
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
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var null|ConditionalOperator::*
     */
    private $ConditionalOperator;

    /**
     * A condition that must be satisfied in order for a conditional `PutItem` operation to succeed.
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
     *   Item?: array<string, AttributeValue>,
     *   Expected?: array<string, ExpectedAttributeValue>,
     *   ReturnValues?: ReturnValue::*,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->TableName = $input['TableName'] ?? null;

        if (isset($input['Item'])) {
            $this->Item = [];
            foreach ($input['Item'] as $key => $item) {
                $this->Item[$key] = AttributeValue::create($item);
            }
        }

        if (isset($input['Expected'])) {
            $this->Expected = [];
            foreach ($input['Expected'] as $key => $item) {
                $this->Expected[$key] = ExpectedAttributeValue::create($item);
            }
        }
        $this->ReturnValues = $input['ReturnValues'] ?? null;
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->ReturnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        $this->ConditionalOperator = $input['ConditionalOperator'] ?? null;
        $this->ConditionExpression = $input['ConditionExpression'] ?? null;
        $this->ExpressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;

        if (isset($input['ExpressionAttributeValues'])) {
            $this->ExpressionAttributeValues = [];
            foreach ($input['ExpressionAttributeValues'] as $key => $item) {
                $this->ExpressionAttributeValues[$key] = AttributeValue::create($item);
            }
        }
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
    public function getItem(): array
    {
        return $this->Item ?? [];
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.PutItem',
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
    public function setItem(array $value): self
    {
        $this->Item = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->TableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->Item) {
            throw new InvalidArgument(sprintf('Missing parameter "Item" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Item'] = new \stdClass();
        } else {
            $payload['Item'] = [];
            foreach ($v as $name => $mv) {
                $payload['Item'][$name] = $mv->requestBody();
            }
        }
        if (null !== $v = $this->Expected) {
            if (empty($v)) {
                $payload['Expected'] = new \stdClass();
            } else {
                $payload['Expected'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Expected'][$name] = $mv->requestBody();
                }
            }
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
        if (null !== $v = $this->ConditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
        }
        if (null !== $v = $this->ConditionExpression) {
            $payload['ConditionExpression'] = $v;
        }
        if (null !== $v = $this->ExpressionAttributeNames) {
            if (empty($v)) {
                $payload['ExpressionAttributeNames'] = new \stdClass();
            } else {
                $payload['ExpressionAttributeNames'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ExpressionAttributeNames'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->ExpressionAttributeValues) {
            if (empty($v)) {
                $payload['ExpressionAttributeValues'] = new \stdClass();
            } else {
                $payload['ExpressionAttributeValues'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ExpressionAttributeValues'][$name] = $mv->requestBody();
                }
            }
        }

        return $payload;
    }
}
