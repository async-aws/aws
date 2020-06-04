<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexUpdate;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;

final class UpdateTableInput extends Input
{
    /**
     * An array of attributes that describe the key schema for the table and indexes. If you are adding a new global
     * secondary index to the table, `AttributeDefinitions` must include the key element(s) of the new index.
     *
     * @var AttributeDefinition[]|null
     */
    private $AttributeDefinitions;

    /**
     * The name of the table to be updated.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. When switching from
     * pay-per-request to provisioned capacity, initial provisioned capacity values must be set. The initial provisioned
     * capacity values are estimated based on the consumed read and write capacity of your table and global secondary
     * indexes over the past 30 minutes.
     *
     * @var null|BillingMode::*
     */
    private $BillingMode;

    /**
     * The new provisioned throughput settings for the specified table or index.
     *
     * @var ProvisionedThroughput|null
     */
    private $ProvisionedThroughput;

    /**
     * An array of one or more global secondary indexes for the table. For each index in the array, you can request one
     * action:.
     *
     * @var GlobalSecondaryIndexUpdate[]|null
     */
    private $GlobalSecondaryIndexUpdates;

    /**
     * Represents the DynamoDB Streams configuration for the table.
     *
     * @var StreamSpecification|null
     */
    private $StreamSpecification;

    /**
     * The new server-side encryption settings for the specified table.
     *
     * @var SSESpecification|null
     */
    private $SSESpecification;

    /**
     * A list of replica update actions (create, delete, or update) for the table.
     *
     * @var ReplicationGroupUpdate[]|null
     */
    private $ReplicaUpdates;

    /**
     * @param array{
     *   AttributeDefinitions?: \AsyncAws\DynamoDb\ValueObject\AttributeDefinition[],
     *   TableName?: string,
     *   BillingMode?: \AsyncAws\DynamoDb\Enum\BillingMode::*,
     *   ProvisionedThroughput?: \AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput|array,
     *   GlobalSecondaryIndexUpdates?: \AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexUpdate[],
     *   StreamSpecification?: \AsyncAws\DynamoDb\ValueObject\StreamSpecification|array,
     *   SSESpecification?: \AsyncAws\DynamoDb\ValueObject\SSESpecification|array,
     *   ReplicaUpdates?: \AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AttributeDefinitions = isset($input['AttributeDefinitions']) ? array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions']) : null;
        $this->TableName = $input['TableName'] ?? null;
        $this->BillingMode = $input['BillingMode'] ?? null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->GlobalSecondaryIndexUpdates = isset($input['GlobalSecondaryIndexUpdates']) ? array_map([GlobalSecondaryIndexUpdate::class, 'create'], $input['GlobalSecondaryIndexUpdates']) : null;
        $this->StreamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->SSESpecification = isset($input['SSESpecification']) ? SSESpecification::create($input['SSESpecification']) : null;
        $this->ReplicaUpdates = isset($input['ReplicaUpdates']) ? array_map([ReplicationGroupUpdate::class, 'create'], $input['ReplicaUpdates']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeDefinition[]
     */
    public function getAttributeDefinitions(): array
    {
        return $this->AttributeDefinitions ?? [];
    }

    /**
     * @return BillingMode::*|null
     */
    public function getBillingMode(): ?string
    {
        return $this->BillingMode;
    }

    /**
     * @return GlobalSecondaryIndexUpdate[]
     */
    public function getGlobalSecondaryIndexUpdates(): array
    {
        return $this->GlobalSecondaryIndexUpdates ?? [];
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->ProvisionedThroughput;
    }

    /**
     * @return ReplicationGroupUpdate[]
     */
    public function getReplicaUpdates(): array
    {
        return $this->ReplicaUpdates ?? [];
    }

    public function getSSESpecification(): ?SSESpecification
    {
        return $this->SSESpecification;
    }

    public function getStreamSpecification(): ?StreamSpecification
    {
        return $this->StreamSpecification;
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
            'X-Amz-Target' => 'DynamoDB_20120810.UpdateTable',
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
     * @param AttributeDefinition[] $value
     */
    public function setAttributeDefinitions(array $value): self
    {
        $this->AttributeDefinitions = $value;

        return $this;
    }

    /**
     * @param BillingMode::*|null $value
     */
    public function setBillingMode(?string $value): self
    {
        $this->BillingMode = $value;

        return $this;
    }

    /**
     * @param GlobalSecondaryIndexUpdate[] $value
     */
    public function setGlobalSecondaryIndexUpdates(array $value): self
    {
        $this->GlobalSecondaryIndexUpdates = $value;

        return $this;
    }

    public function setProvisionedThroughput(?ProvisionedThroughput $value): self
    {
        $this->ProvisionedThroughput = $value;

        return $this;
    }

    /**
     * @param ReplicationGroupUpdate[] $value
     */
    public function setReplicaUpdates(array $value): self
    {
        $this->ReplicaUpdates = $value;

        return $this;
    }

    public function setSSESpecification(?SSESpecification $value): self
    {
        $this->SSESpecification = $value;

        return $this;
    }

    public function setStreamSpecification(?StreamSpecification $value): self
    {
        $this->StreamSpecification = $value;

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
        if (null !== $v = $this->AttributeDefinitions) {
            $index = -1;
            $payload['AttributeDefinitions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributeDefinitions'][$index] = $listValue->requestBody();
            }
        }
        if (null === $v = $this->TableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->BillingMode) {
            if (!BillingMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
            }
            $payload['BillingMode'] = $v;
        }
        if (null !== $v = $this->ProvisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->GlobalSecondaryIndexUpdates) {
            $index = -1;
            $payload['GlobalSecondaryIndexUpdates'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GlobalSecondaryIndexUpdates'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->StreamSpecification) {
            $payload['StreamSpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->SSESpecification) {
            $payload['SSESpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->ReplicaUpdates) {
            $index = -1;
            $payload['ReplicaUpdates'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ReplicaUpdates'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
