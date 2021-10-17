<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\Tag;

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
     * The name of the table to create.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * Specifies the attributes that make up the primary key for a table or an index. The attributes in `KeySchema` must
     * also be defined in the `AttributeDefinitions` array. For more information, see Data Model in the *Amazon DynamoDB
     * Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DataModel.html
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
     * @var LocalSecondaryIndex[]|null
     */
    private $localSecondaryIndexes;

    /**
     * One or more global secondary indexes (the maximum is 20) to be created on the table. Each global secondary index in
     * the array includes the following:.
     *
     * @var GlobalSecondaryIndex[]|null
     */
    private $globalSecondaryIndexes;

    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
     *
     * @var null|BillingMode::*
     */
    private $billingMode;

    /**
     * Represents the provisioned throughput settings for a specified table or index. The settings can be modified using the
     * `UpdateTable` operation.
     *
     * @var ProvisionedThroughput|null
     */
    private $provisionedThroughput;

    /**
     * The settings for DynamoDB Streams on the table. These settings consist of:.
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
     * A list of key-value pairs to label the table. For more information, see Tagging for DynamoDB.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Tagging.html
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   AttributeDefinitions?: AttributeDefinition[],
     *   TableName?: string,
     *   KeySchema?: KeySchemaElement[],
     *   LocalSecondaryIndexes?: LocalSecondaryIndex[],
     *   GlobalSecondaryIndexes?: GlobalSecondaryIndex[],
     *   BillingMode?: BillingMode::*,
     *   ProvisionedThroughput?: ProvisionedThroughput|array,
     *   StreamSpecification?: StreamSpecification|array,
     *   SSESpecification?: SSESpecification|array,
     *   Tags?: Tag[],
     *   @region?: string,
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

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
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
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
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

    public function setProvisionedThroughput(?ProvisionedThroughput $value): self
    {
        $this->provisionedThroughput = $value;

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

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->attributeDefinitions) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeDefinitions" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['AttributeDefinitions'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['AttributeDefinitions'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->tableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->keySchema) {
            throw new InvalidArgument(sprintf('Missing parameter "KeySchema" for "%s". The value cannot be null.', __CLASS__));
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
                throw new InvalidArgument(sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
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

        return $payload;
    }
}
