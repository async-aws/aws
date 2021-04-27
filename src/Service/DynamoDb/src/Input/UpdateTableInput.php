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

/**
 * Represents the input of an `UpdateTable` operation.
 */
final class UpdateTableInput extends Input
{
    /**
     * An array of attributes that describe the key schema for the table and indexes. If you are adding a new global
     * secondary index to the table, `AttributeDefinitions` must include the key element(s) of the new index.
     *
     * @var AttributeDefinition[]|null
     */
    private $attributeDefinitions;

    /**
     * The name of the table to be updated.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. When switching from
     * pay-per-request to provisioned capacity, initial provisioned capacity values must be set. The initial provisioned
     * capacity values are estimated based on the consumed read and write capacity of your table and global secondary
     * indexes over the past 30 minutes.
     *
     * @var null|BillingMode::*
     */
    private $billingMode;

    /**
     * The new provisioned throughput settings for the specified table or index.
     *
     * @var ProvisionedThroughput|null
     */
    private $provisionedThroughput;

    /**
     * An array of one or more global secondary indexes for the table. For each index in the array, you can request one
     * action:.
     *
     * @var GlobalSecondaryIndexUpdate[]|null
     */
    private $globalSecondaryIndexUpdates;

    /**
     * Represents the DynamoDB Streams configuration for the table.
     *
     * @var StreamSpecification|null
     */
    private $streamSpecification;

    /**
     * The new server-side encryption settings for the specified table.
     *
     * @var SSESpecification|null
     */
    private $sseSpecification;

    /**
     * A list of replica update actions (create, delete, or update) for the table.
     *
     * @var ReplicationGroupUpdate[]|null
     */
    private $replicaUpdates;

    /**
     * @param array{
     *   AttributeDefinitions?: AttributeDefinition[],
     *   TableName?: string,
     *   BillingMode?: BillingMode::*,
     *   ProvisionedThroughput?: ProvisionedThroughput|array,
     *   GlobalSecondaryIndexUpdates?: GlobalSecondaryIndexUpdate[],
     *   StreamSpecification?: StreamSpecification|array,
     *   SSESpecification?: SSESpecification|array,
     *   ReplicaUpdates?: ReplicationGroupUpdate[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->attributeDefinitions = isset($input['AttributeDefinitions']) ? array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions']) : null;
        $this->tableName = $input['TableName'] ?? null;
        $this->billingMode = $input['BillingMode'] ?? null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->globalSecondaryIndexUpdates = isset($input['GlobalSecondaryIndexUpdates']) ? array_map([GlobalSecondaryIndexUpdate::class, 'create'], $input['GlobalSecondaryIndexUpdates']) : null;
        $this->streamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->sseSpecification = isset($input['SSESpecification']) ? SSESpecification::create($input['SSESpecification']) : null;
        $this->replicaUpdates = isset($input['ReplicaUpdates']) ? array_map([ReplicationGroupUpdate::class, 'create'], $input['ReplicaUpdates']) : null;
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
        return $this->attributeDefinitions ?? [];
    }

    /**
     * @return BillingMode::*|null
     */
    public function getBillingMode(): ?string
    {
        return $this->billingMode;
    }

    /**
     * @return GlobalSecondaryIndexUpdate[]
     */
    public function getGlobalSecondaryIndexUpdates(): array
    {
        return $this->globalSecondaryIndexUpdates ?? [];
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
    }

    /**
     * @return ReplicationGroupUpdate[]
     */
    public function getReplicaUpdates(): array
    {
        return $this->replicaUpdates ?? [];
    }

    public function getSseSpecification(): ?SSESpecification
    {
        return $this->sseSpecification;
    }

    public function getStreamSpecification(): ?StreamSpecification
    {
        return $this->streamSpecification;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
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
        $body = empty($bodyPayload) ? '{}' : \json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param AttributeDefinition[] $value
     */
    public function setAttributeDefinitions(array $value): self
    {
        $this->attributeDefinitions = $value;

        return $this;
    }

    /**
     * @param BillingMode::*|null $value
     */
    public function setBillingMode(?string $value): self
    {
        $this->billingMode = $value;

        return $this;
    }

    /**
     * @param GlobalSecondaryIndexUpdate[] $value
     */
    public function setGlobalSecondaryIndexUpdates(array $value): self
    {
        $this->globalSecondaryIndexUpdates = $value;

        return $this;
    }

    public function setProvisionedThroughput(?ProvisionedThroughput $value): self
    {
        $this->provisionedThroughput = $value;

        return $this;
    }

    /**
     * @param ReplicationGroupUpdate[] $value
     */
    public function setReplicaUpdates(array $value): self
    {
        $this->replicaUpdates = $value;

        return $this;
    }

    public function setSseSpecification(?SSESpecification $value): self
    {
        $this->sseSpecification = $value;

        return $this;
    }

    public function setStreamSpecification(?StreamSpecification $value): self
    {
        $this->streamSpecification = $value;

        return $this;
    }

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->attributeDefinitions) {
            $index = -1;
            $payload['AttributeDefinitions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributeDefinitions'][$index] = $listValue->requestBody();
            }
        }
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->billingMode) {
            if (!BillingMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
            }
            $payload['BillingMode'] = $v;
        }
        if (null !== $v = $this->provisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->globalSecondaryIndexUpdates) {
            $index = -1;
            $payload['GlobalSecondaryIndexUpdates'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GlobalSecondaryIndexUpdates'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->streamSpecification) {
            $payload['StreamSpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->sseSpecification) {
            $payload['SSESpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->replicaUpdates) {
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
