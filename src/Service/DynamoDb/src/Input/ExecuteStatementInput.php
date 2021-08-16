<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
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
     * @param array{
     *   Statement?: string,
     *   Parameters?: AttributeValue[],
     *   ConsistentRead?: bool,
     *   NextToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->statement = $input['Statement'] ?? null;
        $this->parameters = isset($input['Parameters']) ? array_map([AttributeValue::class, 'create'], $input['Parameters']) : null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsistentRead(): ?bool
    {
        return $this->consistentRead;
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

    public function setStatement(?string $value): self
    {
        $this->statement = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->statement) {
            throw new InvalidArgument(sprintf('Missing parameter "Statement" for "%s". The value cannot be null.', __CLASS__));
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

        return $payload;
    }
}
