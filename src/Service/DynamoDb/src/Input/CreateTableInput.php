<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
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

class CreateTableInput
{
    /**
     * An array of attributes that describe the key schema for the table and indexes.
     *
     * @required
     *
     * @var AttributeDefinition[]
     */
    private $AttributeDefinitions;

    /**
     * The name of the table to create.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * Specifies the attributes that make up the primary key for a table or an index. The attributes in `KeySchema` must
     * also be defined in the `AttributeDefinitions` array. For more information, see Data Model in the *Amazon DynamoDB
     * Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DataModel.html
     * @required
     *
     * @var KeySchemaElement[]
     */
    private $KeySchema;

    /**
     * One or more local secondary indexes (the maximum is 5) to be created on the table. Each index is scoped to a given
     * partition key value. There is a 10 GB size limit per partition key value; otherwise, the size of a local secondary
     * index is unconstrained.
     *
     * @var LocalSecondaryIndex[]
     */
    private $LocalSecondaryIndexes;

    /**
     * One or more global secondary indexes (the maximum is 20) to be created on the table. Each global secondary index in
     * the array includes the following:.
     *
     * @var GlobalSecondaryIndex[]
     */
    private $GlobalSecondaryIndexes;

    /**
     * Controls how you are charged for read and write throughput and how you manage capacity. This setting can be changed
     * later.
     *
     * @var BillingMode::*|null
     */
    private $BillingMode;

    /**
     * Represents the provisioned throughput settings for a specified table or index. The settings can be modified using the
     * `UpdateTable` operation.
     *
     * @var ProvisionedThroughput|null
     */
    private $ProvisionedThroughput;

    /**
     * The settings for DynamoDB Streams on the table. These settings consist of:.
     *
     * @var StreamSpecification|null
     */
    private $StreamSpecification;

    /**
     * Represents the settings used to enable server-side encryption.
     *
     * @var SSESpecification|null
     */
    private $SSESpecification;

    /**
     * A list of key-value pairs to label the table. For more information, see Tagging for DynamoDB.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Tagging.html
     *
     * @var Tag[]
     */
    private $Tags;

    /**
     * @param array{
     *   AttributeDefinitions?: \AsyncAws\DynamoDb\ValueObject\AttributeDefinition[],
     *   TableName?: string,
     *   KeySchema?: \AsyncAws\DynamoDb\ValueObject\KeySchemaElement[],
     *   LocalSecondaryIndexes?: \AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex[],
     *   GlobalSecondaryIndexes?: \AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndex[],
     *   BillingMode?: \AsyncAws\DynamoDb\Enum\BillingMode::*,
     *   ProvisionedThroughput?: \AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput|array,
     *   StreamSpecification?: \AsyncAws\DynamoDb\ValueObject\StreamSpecification|array,
     *   SSESpecification?: \AsyncAws\DynamoDb\ValueObject\SSESpecification|array,
     *   Tags?: \AsyncAws\DynamoDb\ValueObject\Tag[],
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AttributeDefinitions = array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions'] ?? []);
        $this->TableName = $input['TableName'] ?? null;
        $this->KeySchema = array_map([KeySchemaElement::class, 'create'], $input['KeySchema'] ?? []);
        $this->LocalSecondaryIndexes = array_map([LocalSecondaryIndex::class, 'create'], $input['LocalSecondaryIndexes'] ?? []);
        $this->GlobalSecondaryIndexes = array_map([GlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes'] ?? []);
        $this->BillingMode = $input['BillingMode'] ?? null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->StreamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->SSESpecification = isset($input['SSESpecification']) ? SSESpecification::create($input['SSESpecification']) : null;
        $this->Tags = array_map([Tag::class, 'create'], $input['Tags'] ?? []);
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
        return $this->AttributeDefinitions;
    }

    /**
     * @return BillingMode::*|null
     */
    public function getBillingMode(): ?string
    {
        return $this->BillingMode;
    }

    /**
     * @return GlobalSecondaryIndex[]
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->GlobalSecondaryIndexes;
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->KeySchema;
    }

    /**
     * @return LocalSecondaryIndex[]
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->LocalSecondaryIndexes;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->ProvisionedThroughput;
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
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags;
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
     * @param GlobalSecondaryIndex[] $value
     */
    public function setGlobalSecondaryIndexes(array $value): self
    {
        $this->GlobalSecondaryIndexes = $value;

        return $this;
    }

    /**
     * @param KeySchemaElement[] $value
     */
    public function setKeySchema(array $value): self
    {
        $this->KeySchema = $value;

        return $this;
    }

    /**
     * @param LocalSecondaryIndex[] $value
     */
    public function setLocalSecondaryIndexes(array $value): self
    {
        $this->LocalSecondaryIndexes = $value;

        return $this;
    }

    public function setProvisionedThroughput(?ProvisionedThroughput $value): self
    {
        $this->ProvisionedThroughput = $value;

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

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    /**
     * @internal
     */
    private function requestBody(): array
    {
        $payload = [];

        $index = -1;
        foreach ($this->AttributeDefinitions as $listValue) {
            ++$index;
            $payload['AttributeDefinitions'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->TableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;

        $index = -1;
        foreach ($this->KeySchema as $listValue) {
            ++$index;
            $payload['KeySchema'][$index] = $listValue->requestBody();
        }

        $index = -1;
        foreach ($this->LocalSecondaryIndexes as $listValue) {
            ++$index;
            $payload['LocalSecondaryIndexes'][$index] = $listValue->requestBody();
        }

        $index = -1;
        foreach ($this->GlobalSecondaryIndexes as $listValue) {
            ++$index;
            $payload['GlobalSecondaryIndexes'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->BillingMode) {
            if (!BillingMode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "BillingMode" for "%s". The value "%s" is not a valid "BillingMode".', __CLASS__, $v));
            }
            $payload['BillingMode'] = $v;
        }
        if (null !== $v = $this->ProvisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->StreamSpecification) {
            $payload['StreamSpecification'] = $v->requestBody();
        }
        if (null !== $v = $this->SSESpecification) {
            $payload['SSESpecification'] = $v->requestBody();
        }

        $index = -1;
        foreach ($this->Tags as $listValue) {
            ++$index;
            $payload['Tags'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
