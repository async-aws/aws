<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

final class ExecuteStatementInput extends Input
{
    /**
     * The PartiQL statement representing the operation to run.
     *
     * @required
     *
     * @var string|null
     */
    private $statement;

    /**
     * The parameters for the PartiQL statement, if any.
     *
     * @var AttributeValue[]|null
     */
    private $parameters;

    /**
     * The consistency of a read operation. If set to `true`, then a strongly consistent read is used; otherwise, an
     * eventually consistent read is used.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * Set this value to get remaining results, if `NextToken` was returned in the statement response.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * The maximum number of items to evaluate (not necessarily the number of matching items). If DynamoDB processes the
     * number of items up to the limit while processing the results, it stops the operation and returns the matching values
     * up to that point, along with a key in `LastEvaluatedKey` to apply in a subsequent operation so you can pick up where
     * you left off. Also, if the processed dataset size exceeds 1 MB before DynamoDB reaches this limit, it stops the
     * operation and returns the matching values up to the limit, and a key in `LastEvaluatedKey` to apply in a subsequent
     * operation to continue the operation.
     *
     * @var int|null
     */
    private $limit;

    /**
     * An optional parameter that returns the item attributes for an `ExecuteStatement` operation that failed a condition
     * check.
     *
     * There is no additional cost associated with requesting a return value aside from the small network and processing
     * overhead of receiving a larger response. No read capacity units are consumed.
     *
     * @var ReturnValuesOnConditionCheckFailure::*|null
     */
    private $returnValuesOnConditionCheckFailure;

    /**
     * @param array{
     *   Statement?: string,
     *   Parameters?: array<AttributeValue|array>|null,
     *   ConsistentRead?: bool|null,
     *   NextToken?: string|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   Limit?: int|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->statement = $input['Statement'] ?? null;
        $this->parameters = isset($input['Parameters']) ? array_map([AttributeValue::class, 'create'], $input['Parameters']) : null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->returnValuesOnConditionCheckFailure = $input['ReturnValuesOnConditionCheckFailure'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Statement?: string,
     *   Parameters?: array<AttributeValue|array>|null,
     *   ConsistentRead?: bool|null,
     *   NextToken?: string|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   Limit?: int|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|ExecuteStatementInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsistentRead(): ?bool
    {
        return $this->consistentRead;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return AttributeValue[]
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @return ReturnValuesOnConditionCheckFailure::*|null
     */
    public function getReturnValuesOnConditionCheckFailure(): ?string
    {
        return $this->returnValuesOnConditionCheckFailure;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.ExecuteStatement',
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

    public function setConsistentRead(?bool $value): self
    {
        $this->consistentRead = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param AttributeValue[] $value
     */
    public function setParameters(array $value): self
    {
        $this->parameters = $value;

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
     * @param ReturnValuesOnConditionCheckFailure::*|null $value
     */
    public function setReturnValuesOnConditionCheckFailure(?string $value): self
    {
        $this->returnValuesOnConditionCheckFailure = $value;

        return $this;
    }

    public function setStatement(?string $value): self
    {
        $this->statement = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->statement) {
            throw new InvalidArgument(\sprintf('Missing parameter "Statement" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Statement'] = $v;
        if (null !== $v = $this->parameters) {
            $index = -1;
            $payload['Parameters'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Parameters'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->consistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->returnValuesOnConditionCheckFailure) {
            if (!ReturnValuesOnConditionCheckFailure::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnValuesOnConditionCheckFailure" for "%s". The value "%s" is not a valid "ReturnValuesOnConditionCheckFailure".', __CLASS__, $v));
            }
            $payload['ReturnValuesOnConditionCheckFailure'] = $v;
        }

        return $payload;
    }
}
