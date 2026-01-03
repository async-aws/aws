<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\Enum\TableClass;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\OnDemandThroughput;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\Tag;
use AsyncAws\DynamoDb\ValueObject\WarmThroughput;

/**
 * Represents the input of a `CreateTable` operation.
 */
final class CreateTableInput extends Input
{
    /**
     * An array of attributes that describe the key schema for the table and indexes.
     *
     * @required
     *
     * @var AttributeDefinition[]|null
     */
    private $attributeDefinitions;

    /**
     * The name of the table to create. You can also provide the Amazon Resource Name (ARN) of the table in this parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * Specifies the attributes that make up the primary key for a table or an index. The attributes in `KeySchema` must
     * also be defined in the `AttributeDefinitions` array. For more information, see Data Model [^1] in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * Each `KeySchemaElement` in the array is composed of:
     *
     * - `AttributeName` - The name of this key attribute.
     * - `KeyType` - The role that the key attribute will assume:
     *
     *   - `HASH` - partition key
     *   - `RANGE` - sort key
     *
     *
     * > The partition key of an item is also known as its *hash attribute*. The term "hash attribute" derives from the
     * > DynamoDB usage of an internal hash function to evenly distribute data items across partitions, based on their
     * > partition key values.
     * >
     * > The sort key of an item is also known as its *range attribute*. The term "range attribute" derives from the way
     * > DynamoDB stores items with the same partition key physically close together, in sorted order by the sort key value.
     *
     * For a simple primary key (partition key), you must provide exactly one element with a `KeyType` of `HASH`.
     *
     * For a composite primary key (partition key and sort key), you must provide exactly two elements, in this order: The
     * first element must have a `KeyType` of `HASH`, and the second element must have a `KeyType` of `RANGE`.
     *
     * For more information, see Working with Tables [^2] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DataModel.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#WorkingWithTables.primary.key
     *
     * @required
     *
     * @var KeySchemaElement[]|null
     */
    private $keySchema;

    /**
     * One or more local secondary indexes (the maximum is 5) to be created on the table. Each index is scoped to a given
     * partition key value. There is a 10 GB size limit per partition key value; otherwise, the size of a local secondary
     * index is unconstrained.
     *
     * Each local secondary index in the array includes the following:
     *
     * - `IndexName` - The name of the local secondary index. Must be unique only for this table.
     * - `KeySchema` - Specifies the key schema for the local secondary index. The key schema must begin with the same
     *   partition key as the table.
     * - `Projection` - Specifies attributes that are copied (projected) from the table into the index. These are in
     *   addition to the primary key attributes and index key attributes, which are automatically projected. Each attribute
     *   specification is composed of:
     *
     *   - `ProjectionType` - One of the following:
     *
     *     - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
     *     - `INCLUDE` - Only the specified table attributes are projected into the index. The list of projected attributes
     *       is in `NonKeyAttributes`.
     *     - `ALL` - All of the table attributes are projected into the index.
     *
     *   - `NonKeyAttributes` - A list of one or more non-key attribute names that are projected into the secondary index.
     *     The total count of attributes provided in `NonKeyAttributes`, summed across all of the secondary indexes, must
     *     not exceed 100. If you project the same attribute into two different indexes, this counts as two distinct
     *     attributes when determining the total. This limit only applies when you specify the ProjectionType of `INCLUDE`.
     *     You still can specify the ProjectionType of `ALL` to project all attributes from the source table, even if the
     *     table has more than 100 attributes.
     *
     * @var LocalSecondaryIndex[]|null
     */
    private $localSecondaryIndexes;

    /**
     * One or more global secondary indexes (the maximum is 20) to be created on the table. Each global secondary index in
     * the array includes the following:
     *
     * - `IndexName` - The name of the global secondary index. Must be unique only for this table.
     * - `KeySchema` - Specifies the key schema for the global secondary index.
     * - `Projection` - Specifies attributes that are copied (projected) from the table into the index. These are in
     *   addition to the primary key attributes and index key attributes, which are automatically projected. Each attribute
     *   specification is composed of:
     *
     *   - `ProjectionType` - One of the following:
     *
     *     - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
     *     - `INCLUDE` - Only the specified table attributes are projected into the index. The list of projected attributes
     *       is in `NonKeyAttributes`.
     *     - `ALL` - All of the table attributes are projected into the index.
     *
     *   - `NonKeyAttributes` - A list of one or more non-key attribute names that are projected into the secondary index.
     *     The total count of attributes provided in `NonKeyAttributes`, summed across all of the secondary indexes, must
     *     not exceed 100. If you project the same attribute into two different indexes, this counts as two distinct
     *     attributes when determining the total. This limit only applies when you specify the ProjectionType of `INCLUDE`.
     *     You still can specify the ProjectionType of `ALL` to project all attributes from the source table, even if the
     *     table has more than 100 attributes.
     *
     * - `ProvisionedThroughput` - The provisioned throughput settings for the global secondary index, consisting of read
     *   and write capacity units.
     *
     * @var GlobalSecondaryIndex[]|null
     */
    private $globalSecondaryIndexes;

    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
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
     * Represents the provisioned throughput settings for a specified table or index. The settings can be modified using the
     * `UpdateTable` operation.
     *
     * If you set BillingMode as `PROVISIONED`, you must specify this property. If you set BillingMode as `PAY_PER_REQUEST`,
     * you cannot specify this property.
     *
     * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     *
     * @var ProvisionedThroughput|null
     */
    private $provisionedThroughput;

    /**
     * The settings for DynamoDB Streams on the table. These settings consist of:
     *
     * - `StreamEnabled` - Indicates whether DynamoDB Streams is to be enabled (true) or disabled (false).
     * - `StreamViewType` - When an item in the table is modified, `StreamViewType` determines what information is written
     *   to the table's stream. Valid values for `StreamViewType` are:
     *
     *   - `KEYS_ONLY` - Only the key attributes of the modified item are written to the stream.
     *   - `NEW_IMAGE` - The entire item, as it appears after it was modified, is written to the stream.
     *   - `OLD_IMAGE` - The entire item, as it appeared before it was modified, is written to the stream.
     *   - `NEW_AND_OLD_IMAGES` - Both the new and the old item images of the item are written to the stream.
     *
     * @var StreamSpecification|null
     */
    private $streamSpecification;

    /**
     * Represents the settings used to enable server-side encryption.
     *
     * @var SSESpecification|null
     */
    private $sseSpecification;

    /**
     * A list of key-value pairs to label the table. For more information, see Tagging for DynamoDB [^1].
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Tagging.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * The table class of the new table. Valid values are `STANDARD` and `STANDARD_INFREQUENT_ACCESS`.
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
     * Represents the warm throughput (in read units per second and write units per second) for creating a table.
     *
     * @var WarmThroughput|null
     */
    private $warmThroughput;

    /**
     * An Amazon Web Services resource-based policy document in JSON format that will be attached to the table.
     *
     * When you attach a resource-based policy while creating a table, the policy application is *strongly consistent*.
     *
     * The maximum size supported for a resource-based policy document is 20 KB. DynamoDB counts whitespaces when
     * calculating the size of a policy against this limit. For a full list of all considerations that apply for
     * resource-based policies, see Resource-based policy considerations [^1].
     *
     * > You need to specify the `CreateTable` and `PutResourcePolicy` IAM actions for authorizing a user to create a table
     * > with a resource-based policy.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/rbac-considerations.html
     *
     * @var string|null
     */
    private $resourcePolicy;

    /**
     * Sets the maximum number of read and write units for the specified table in on-demand capacity mode. If you use this
     * parameter, you must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * @param array{
     *   AttributeDefinitions?: array<AttributeDefinition|array>,
     *   TableName?: string,
     *   KeySchema?: array<KeySchemaElement|array>,
     *   LocalSecondaryIndexes?: array<LocalSecondaryIndex|array>|null,
     *   GlobalSecondaryIndexes?: array<GlobalSecondaryIndex|array>|null,
     *   BillingMode?: BillingMode::*|null,
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   StreamSpecification?: StreamSpecification|array|null,
     *   SSESpecification?: SSESpecification|array|null,
     *   Tags?: array<Tag|array>|null,
     *   TableClass?: TableClass::*|null,
     *   DeletionProtectionEnabled?: bool|null,
     *   WarmThroughput?: WarmThroughput|array|null,
     *   ResourcePolicy?: string|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->attributeDefinitions = isset($input['AttributeDefinitions']) ? array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions']) : null;
        $this->tableName = $input['TableName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->localSecondaryIndexes = isset($input['LocalSecondaryIndexes']) ? array_map([LocalSecondaryIndex::class, 'create'], $input['LocalSecondaryIndexes']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([GlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->billingMode = $input['BillingMode'] ?? null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->streamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->sseSpecification = isset($input['SSESpecification']) ? SSESpecification::create($input['SSESpecification']) : null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->tableClass = $input['TableClass'] ?? null;
        $this->deletionProtectionEnabled = $input['DeletionProtectionEnabled'] ?? null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? WarmThroughput::create($input['WarmThroughput']) : null;
        $this->resourcePolicy = $input['ResourcePolicy'] ?? null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AttributeDefinitions?: array<AttributeDefinition|array>,
     *   TableName?: string,
     *   KeySchema?: array<KeySchemaElement|array>,
     *   LocalSecondaryIndexes?: array<LocalSecondaryIndex|array>|null,
     *   GlobalSecondaryIndexes?: array<GlobalSecondaryIndex|array>|null,
     *   BillingMode?: BillingMode::*|null,
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   StreamSpecification?: StreamSpecification|array|null,
     *   SSESpecification?: SSESpecification|array|null,
     *   Tags?: array<Tag|array>|null,
     *   TableClass?: TableClass::*|null,
     *   DeletionProtectionEnabled?: bool|null,
     *   WarmThroughput?: WarmThroughput|array|null,
     *   ResourcePolicy?: string|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   '@region'?: string|null,
     * }|CreateTableInput $input
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
     * @return GlobalSecondaryIndex[]
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->globalSecondaryIndexes ?? [];
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->keySchema ?? [];
    }

    /**
     * @return LocalSecondaryIndex[]
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->localSecondaryIndexes ?? [];
    }

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
    }

    public function getResourcePolicy(): ?string
    {
        return $this->resourcePolicy;
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

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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
            'X-Amz-Target' => 'DynamoDB_20120810.CreateTable',
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
     * @param GlobalSecondaryIndex[] $value
     */
    public function setGlobalSecondaryIndexes(array $value): self
    {
        $this->globalSecondaryIndexes = $value;

        return $this;
    }

    /**
     * @param KeySchemaElement[] $value
     */
    public function setKeySchema(array $value): self
    {
        $this->keySchema = $value;

        return $this;
    }

    /**
     * @param LocalSecondaryIndex[] $value
     */
    public function setLocalSecondaryIndexes(array $value): self
    {
        $this->localSecondaryIndexes = $value;

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

    public function setResourcePolicy(?string $value): self
    {
        $this->resourcePolicy = $value;

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

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

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
        if (null === $v = $this->attributeDefinitions) {
            throw new InvalidArgument(\sprintf('Missing parameter "AttributeDefinitions" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['AttributeDefinitions'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['AttributeDefinitions'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->tableName) {
            throw new InvalidArgument(\sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->keySchema) {
            throw new InvalidArgument(\sprintf('Missing parameter "KeySchema" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['KeySchema'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['KeySchema'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->localSecondaryIndexes) {
            $index = -1;
            $payload['LocalSecondaryIndexes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['LocalSecondaryIndexes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->globalSecondaryIndexes) {
            $index = -1;
            $payload['GlobalSecondaryIndexes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GlobalSecondaryIndexes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->billingMode) {
            if (!BillingMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
            }
            $payload['BillingMode'] = $v;
        }
        if (null !== $v = $this->provisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->streamSpecification) {
            $payload['StreamSpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->sseSpecification) {
            $payload['SSESpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['Tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Tags'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->tableClass) {
            if (!TableClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "TableClass" for "%s". The value "%s" is not a valid "TableClass".', __CLASS__, $v));
            }
            $payload['TableClass'] = $v;
        }
        if (null !== $v = $this->deletionProtectionEnabled) {
            $payload['DeletionProtectionEnabled'] = (bool) $v;
        }
        if (null !== $v = $this->warmThroughput) {
            $payload['WarmThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->resourcePolicy) {
            $payload['ResourcePolicy'] = $v;
        }
        if (null !== $v = $this->onDemandThroughput) {
            $payload['OnDemandThroughput'] = $v->requestBody();
        }

        return $payload;
    }
}
