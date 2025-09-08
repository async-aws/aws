<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\Enum\MultiRegionConsistency;
use AsyncAws\DynamoDb\Enum\TableClass;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexUpdate;
use AsyncAws\DynamoDb\ValueObject\GlobalTableWitnessGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\OnDemandThroughput;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\WarmThroughput;

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
     * The name of the table to be updated. You can also provide the Amazon Resource Name (ARN) of the table in this
     * parameter.
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
     * - `PAY_PER_REQUEST` - We recommend using `PAY_PER_REQUEST` for most DynamoDB workloads. `PAY_PER_REQUEST` sets the
     *   billing mode to On-demand capacity mode [^1].
     * - `PROVISIONED` - We recommend using `PROVISIONED` for steady workloads with predictable growth where capacity
     *   requirements can be reliably forecasted. `PROVISIONED` sets the billing mode to Provisioned capacity mode [^2].
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/on-demand-capacity-mode.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/provisioned-capacity-mode.html
     *
     * @var BillingMode::*|null
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
     * action:
     *
     * - `Create` - add a new global secondary index to the table.
     * - `Update` - modify the provisioned throughput settings of an existing global secondary index.
     * - `Delete` - remove a global secondary index from the table.
     *
     * You can create or delete only one global secondary index per `UpdateTable` operation.
     *
     * For more information, see Managing Global Secondary Indexes [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GSI.OnlineOps.html
     *
     * @var GlobalSecondaryIndexUpdate[]|null
     */
    private $globalSecondaryIndexUpdates;

    /**
     * Represents the DynamoDB Streams configuration for the table.
     *
     * > You receive a `ValidationException` if you try to enable a stream on a table that already has a stream, or if you
     * > try to disable a stream on a table that doesn't have a stream.
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
     * The table class of the table to be updated. Valid values are `STANDARD` and `STANDARD_INFREQUENT_ACCESS`.
     *
     * @var TableClass::*|null
     */
    private $tableClass;

    /**
     * Indicates whether deletion protection is to be enabled (true) or disabled (false) on the table.
     *
     * @var bool|null
     */
    private $deletionProtectionEnabled;

    /**
     * Specifies the consistency mode for a new global table. This parameter is only valid when you create a global table by
     * specifying one or more Create [^1] actions in the ReplicaUpdates [^2] action list.
     *
     * You can specify one of the following consistency modes:
     *
     * - `EVENTUAL`: Configures a new global table for multi-Region eventual consistency (MREC). This is the default
     *   consistency mode for global tables.
     * - `STRONG`: Configures a new global table for multi-Region strong consistency (MRSC).
     *
     * If you don't specify this field, the global table consistency mode defaults to `EVENTUAL`. For more information about
     * global tables consistency modes, see Consistency modes [^3] in DynamoDB developer guide.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ReplicationGroupUpdate.html#DDB-Type-ReplicationGroupUpdate-Create
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTable.html#DDB-UpdateTable-request-ReplicaUpdates
     * [^3]: https://docs.aws.amazon.com/V2globaltables_HowItWorks.html#V2globaltables_HowItWorks.consistency-modes
     *
     * @var MultiRegionConsistency::*|null
     */
    private $multiRegionConsistency;

    /**
     * A list of witness updates for a MRSC global table. A witness provides a cost-effective alternative to a full replica
     * in a MRSC global table by maintaining replicated change data written to global table replicas. You cannot perform
     * read or write operations on a witness. For each witness, you can request one action:
     *
     * - `Create` - add a new witness to the global table.
     * - `Delete` - remove a witness from the global table.
     *
     * You can create or delete only one witness per `UpdateTable` operation.
     *
     * For more information, see Multi-Region strong consistency (MRSC) [^1] in the Amazon DynamoDB Developer Guide
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/V2globaltables_HowItWorks.html#V2globaltables_HowItWorks.consistency-modes
     *
     * @var GlobalTableWitnessGroupUpdate[]|null
     */
    private $globalTableWitnessUpdates;

    /**
     * Updates the maximum number of read and write units for the specified table in on-demand capacity mode. If you use
     * this parameter, you must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * Represents the warm throughput (in read units per second and write units per second) for updating a table.
     *
     * @var WarmThroughput|null
     */
    private $warmThroughput;

    /**
     * @param array{
     *   AttributeDefinitions?: array<AttributeDefinition|array>|null,
     *   TableName?: string,
     *   BillingMode?: BillingMode::*|null,
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   GlobalSecondaryIndexUpdates?: array<GlobalSecondaryIndexUpdate|array>|null,
     *   StreamSpecification?: StreamSpecification|array|null,
     *   SSESpecification?: SSESpecification|array|null,
     *   ReplicaUpdates?: array<ReplicationGroupUpdate|array>|null,
     *   TableClass?: TableClass::*|null,
     *   DeletionProtectionEnabled?: bool|null,
     *   MultiRegionConsistency?: MultiRegionConsistency::*|null,
     *   GlobalTableWitnessUpdates?: array<GlobalTableWitnessGroupUpdate|array>|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: WarmThroughput|array|null,
     *   '@region'?: string|null,
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
        $this->tableClass = $input['TableClass'] ?? null;
        $this->deletionProtectionEnabled = $input['DeletionProtectionEnabled'] ?? null;
        $this->multiRegionConsistency = $input['MultiRegionConsistency'] ?? null;
        $this->globalTableWitnessUpdates = isset($input['GlobalTableWitnessUpdates']) ? array_map([GlobalTableWitnessGroupUpdate::class, 'create'], $input['GlobalTableWitnessUpdates']) : null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? WarmThroughput::create($input['WarmThroughput']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AttributeDefinitions?: array<AttributeDefinition|array>|null,
     *   TableName?: string,
     *   BillingMode?: BillingMode::*|null,
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   GlobalSecondaryIndexUpdates?: array<GlobalSecondaryIndexUpdate|array>|null,
     *   StreamSpecification?: StreamSpecification|array|null,
     *   SSESpecification?: SSESpecification|array|null,
     *   ReplicaUpdates?: array<ReplicationGroupUpdate|array>|null,
     *   TableClass?: TableClass::*|null,
     *   DeletionProtectionEnabled?: bool|null,
     *   MultiRegionConsistency?: MultiRegionConsistency::*|null,
     *   GlobalTableWitnessUpdates?: array<GlobalTableWitnessGroupUpdate|array>|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: WarmThroughput|array|null,
     *   '@region'?: string|null,
     * }|UpdateTableInput $input
     */
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

    public function getDeletionProtectionEnabled(): ?bool
    {
        return $this->deletionProtectionEnabled;
    }

    /**
     * @return GlobalSecondaryIndexUpdate[]
     */
    public function getGlobalSecondaryIndexUpdates(): array
    {
        return $this->globalSecondaryIndexUpdates ?? [];
    }

    /**
     * @return GlobalTableWitnessGroupUpdate[]
     */
    public function getGlobalTableWitnessUpdates(): array
    {
        return $this->globalTableWitnessUpdates ?? [];
    }

    /**
     * @return MultiRegionConsistency::*|null
     */
    public function getMultiRegionConsistency(): ?string
    {
        return $this->multiRegionConsistency;
    }

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
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

    /**
     * @return TableClass::*|null
     */
    public function getTableClass(): ?string
    {
        return $this->tableClass;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getWarmThroughput(): ?WarmThroughput
    {
        return $this->warmThroughput;
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

    public function setDeletionProtectionEnabled(?bool $value): self
    {
        $this->deletionProtectionEnabled = $value;

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

    /**
     * @param GlobalTableWitnessGroupUpdate[] $value
     */
    public function setGlobalTableWitnessUpdates(array $value): self
    {
        $this->globalTableWitnessUpdates = $value;

        return $this;
    }

    /**
     * @param MultiRegionConsistency::*|null $value
     */
    public function setMultiRegionConsistency(?string $value): self
    {
        $this->multiRegionConsistency = $value;

        return $this;
    }

    public function setOnDemandThroughput(?OnDemandThroughput $value): self
    {
        $this->onDemandThroughput = $value;

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

    /**
     * @param TableClass::*|null $value
     */
    public function setTableClass(?string $value): self
    {
        $this->tableClass = $value;

        return $this;
    }

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    public function setWarmThroughput(?WarmThroughput $value): self
    {
        $this->warmThroughput = $value;

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
            throw new InvalidArgument(\sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->billingMode) {
            if (!BillingMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
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
        if (null !== $v = $this->tableClass) {
            if (!TableClass::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "TableClass" for "%s". The value "%s" is not a valid "TableClass".', __CLASS__, $v));
            }
            $payload['TableClass'] = $v;
        }
        if (null !== $v = $this->deletionProtectionEnabled) {
            $payload['DeletionProtectionEnabled'] = (bool) $v;
        }
        if (null !== $v = $this->multiRegionConsistency) {
            if (!MultiRegionConsistency::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "MultiRegionConsistency" for "%s". The value "%s" is not a valid "MultiRegionConsistency".', __CLASS__, $v));
            }
            $payload['MultiRegionConsistency'] = $v;
        }
        if (null !== $v = $this->globalTableWitnessUpdates) {
            $index = -1;
            $payload['GlobalTableWitnessUpdates'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GlobalTableWitnessUpdates'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->onDemandThroughput) {
            $payload['OnDemandThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->warmThroughput) {
            $payload['WarmThroughput'] = $v->requestBody();
        }

        return $payload;
    }
}
