<?php

namespace AsyncAws\DynamoDb;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\Enum\ReturnValue;
use AsyncAws\DynamoDb\Enum\Select;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\Input\BatchWriteItemInput;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DeleteItemInput;
use AsyncAws\DynamoDb\Input\DeleteTableInput;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\ListTablesInput;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\Input\UpdateItemInput;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\Input\UpdateTimeToLiveInput;
use AsyncAws\DynamoDb\Result\BatchGetItemOutput;
use AsyncAws\DynamoDb\Result\BatchWriteItemOutput;
use AsyncAws\DynamoDb\Result\CreateTableOutput;
use AsyncAws\DynamoDb\Result\DeleteItemOutput;
use AsyncAws\DynamoDb\Result\DeleteTableOutput;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\Result\ListTablesOutput;
use AsyncAws\DynamoDb\Result\PutItemOutput;
use AsyncAws\DynamoDb\Result\QueryOutput;
use AsyncAws\DynamoDb\Result\ScanOutput;
use AsyncAws\DynamoDb\Result\TableExistsWaiter;
use AsyncAws\DynamoDb\Result\TableNotExistsWaiter;
use AsyncAws\DynamoDb\Result\UpdateItemOutput;
use AsyncAws\DynamoDb\Result\UpdateTableOutput;
use AsyncAws\DynamoDb\Result\UpdateTimeToLiveOutput;
use AsyncAws\DynamoDb\ValueObject\AttributeDefinition;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\AttributeValueUpdate;
use AsyncAws\DynamoDb\ValueObject\Condition;
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexUpdate;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\Tag;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;

class DynamoDbClient extends AbstractApi
{
    /**
     * The `BatchGetItem` operation returns the attributes of one or more items from one or more tables. You identify
     * requested items by primary key.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchgetitem
     *
     * @param array{
     *   RequestItems: array<string, KeysAndAttributes>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   @region?: string,
     * }|BatchGetItemInput $input
     */
    public function batchGetItem($input): BatchGetItemOutput
    {
        $input = BatchGetItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchGetItem', 'region' => $input->getRegion()]));

        return new BatchGetItemOutput($response, $this, $input);
    }

    /**
     * The `BatchWriteItem` operation puts or deletes multiple items in one or more tables. A single call to
     * `BatchWriteItem` can write up to 16 MB of data, which can comprise as many as 25 put or delete requests. Individual
     * items to be written can be as large as 400 KB.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchwriteitem
     *
     * @param array{
     *   RequestItems: array<string, array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   @region?: string,
     * }|BatchWriteItemInput $input
     */
    public function batchWriteItem($input): BatchWriteItemOutput
    {
        $input = BatchWriteItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchWriteItem', 'region' => $input->getRegion()]));

        return new BatchWriteItemOutput($response);
    }

    /**
     * The `CreateTable` operation adds a new table to your account. In an AWS account, table names must be unique within
     * each Region. That is, you can have two tables with same name if you create the tables in different Regions.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
     *
     * @param array{
     *   AttributeDefinitions: AttributeDefinition[],
     *   TableName: string,
     *   KeySchema: KeySchemaElement[],
     *   LocalSecondaryIndexes?: LocalSecondaryIndex[],
     *   GlobalSecondaryIndexes?: GlobalSecondaryIndex[],
     *   BillingMode?: BillingMode::*,
     *   ProvisionedThroughput?: ProvisionedThroughput|array,
     *   StreamSpecification?: StreamSpecification|array,
     *   SSESpecification?: SSESpecification|array,
     *   Tags?: Tag[],
     *   @region?: string,
     * }|CreateTableInput $input
     */
    public function createTable($input): CreateTableOutput
    {
        $input = CreateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateTable', 'region' => $input->getRegion()]));

        return new CreateTableOutput($response);
    }

    /**
     * Deletes a single item in a table by primary key. You can perform a conditional delete operation that deletes the item
     * if it exists, or if it has an expected attribute value.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue>,
     *   Expected?: array<string, ExpectedAttributeValue>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ReturnValues?: ReturnValue::*,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
     * }|DeleteItemInput $input
     */
    public function deleteItem($input): DeleteItemOutput
    {
        $input = DeleteItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteItem', 'region' => $input->getRegion()]));

        return new DeleteItemOutput($response);
    }

    /**
     * The `DeleteTable` operation deletes a table and all of its items. After a `DeleteTable` request, the specified table
     * is in the `DELETING` state until DynamoDB completes the deletion. If the table is in the `ACTIVE` state, you can
     * delete it. If a table is in `CREATING` or `UPDATING` states, then DynamoDB returns a `ResourceInUseException`. If the
     * specified table does not exist, DynamoDB returns a `ResourceNotFoundException`. If table is already in the `DELETING`
     * state, no error is returned.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DeleteTableInput $input
     */
    public function deleteTable($input): DeleteTableOutput
    {
        $input = DeleteTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteTable', 'region' => $input->getRegion()]));

        return new DeleteTableOutput($response);
    }

    /**
     * Returns information about the table, including the current status of the table, when it was created, the primary key
     * schema, and any indexes on the table.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describetable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function describeTable($input): DescribeTableOutput
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new DescribeTableOutput($response);
    }

    /**
     * The `GetItem` operation returns a set of attributes for the item with the given primary key. If there is no matching
     * item, `GetItem` does not return any data and there will be no `Item` element in the response.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue>,
     *   AttributesToGet?: string[],
     *   ConsistentRead?: bool,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   @region?: string,
     * }|GetItemInput $input
     */
    public function getItem($input): GetItemOutput
    {
        $input = GetItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetItem', 'region' => $input->getRegion()]));

        return new GetItemOutput($response);
    }

    /**
     * Returns an array of table names associated with the current account and endpoint. The output from `ListTables` is
     * paginated, with each page returning a maximum of 100 table names.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
     *
     * @param array{
     *   ExclusiveStartTableName?: string,
     *   Limit?: int,
     *   @region?: string,
     * }|ListTablesInput $input
     */
    public function listTables($input = []): ListTablesOutput
    {
        $input = ListTablesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTables', 'region' => $input->getRegion()]));

        return new ListTablesOutput($response, $this, $input);
    }

    /**
     * Creates a new item, or replaces an old item with a new item. If an item that has the same primary key as the new item
     * already exists in the specified table, the new item completely replaces the existing item. You can perform a
     * conditional put operation (add a new item if one with the specified primary key doesn't exist), or replace an
     * existing item if it has certain attribute values. You can return the item's attribute values in the same operation,
     * using the `ReturnValues` parameter.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
     *
     * @param array{
     *   TableName: string,
     *   Item: array<string, AttributeValue>,
     *   Expected?: array<string, ExpectedAttributeValue>,
     *   ReturnValues?: ReturnValue::*,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
     * }|PutItemInput $input
     */
    public function putItem($input): PutItemOutput
    {
        $input = PutItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutItem', 'region' => $input->getRegion()]));

        return new PutItemOutput($response);
    }

    /**
     * The `Query` operation finds items based on primary key values. You can query any table or secondary index that has a
     * composite primary key (a partition key and a sort key).
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string,
     *   Select?: Select::*,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   ConsistentRead?: bool,
     *   KeyConditions?: array<string, Condition>,
     *   QueryFilter?: array<string, Condition>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ScanIndexForward?: bool,
     *   ExclusiveStartKey?: array<string, AttributeValue>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   KeyConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
     * }|QueryInput $input
     */
    public function query($input): QueryOutput
    {
        $input = QueryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Query', 'region' => $input->getRegion()]));

        return new QueryOutput($response, $this, $input);
    }

    /**
     * The `Scan` operation returns one or more items and item attributes by accessing every item in a table or a secondary
     * index. To have DynamoDB return fewer items, you can provide a `FilterExpression` operation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   Select?: Select::*,
     *   ScanFilter?: array<string, Condition>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ExclusiveStartKey?: array<string, AttributeValue>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   TotalSegments?: int,
     *   Segment?: int,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   ConsistentRead?: bool,
     *   @region?: string,
     * }|ScanInput $input
     */
    public function scan($input): ScanOutput
    {
        $input = ScanInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Scan', 'region' => $input->getRegion()]));

        return new ScanOutput($response, $this, $input);
    }

    /**
     * Check status of operation describeTable.
     *
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function tableExists($input): TableExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new TableExistsWaiter($response, $this, $input);
    }

    /**
     * Check status of operation describeTable.
     *
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   @region?: string,
     * }|DescribeTableInput $input
     */
    public function tableNotExists($input): TableNotExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion()]));

        return new TableNotExistsWaiter($response, $this, $input);
    }

    /**
     * Edits an existing item's attributes, or adds a new item to the table if it does not already exist. You can put,
     * delete, or add attribute values. You can also perform a conditional update on an existing item (insert a new
     * attribute name-value pair if it doesn't exist, or replace an existing name-value pair if it has certain expected
     * attribute values).
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updateitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue>,
     *   AttributeUpdates?: array<string, AttributeValueUpdate>,
     *   Expected?: array<string, ExpectedAttributeValue>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ReturnValues?: ReturnValue::*,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   UpdateExpression?: string,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
     * }|UpdateItemInput $input
     */
    public function updateItem($input): UpdateItemOutput
    {
        $input = UpdateItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateItem', 'region' => $input->getRegion()]));

        return new UpdateItemOutput($response);
    }

    /**
     * Modifies the provisioned throughput settings, global secondary indexes, or DynamoDB Streams settings for a given
     * table.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updatetable
     *
     * @param array{
     *   AttributeDefinitions?: AttributeDefinition[],
     *   TableName: string,
     *   BillingMode?: BillingMode::*,
     *   ProvisionedThroughput?: ProvisionedThroughput|array,
     *   GlobalSecondaryIndexUpdates?: GlobalSecondaryIndexUpdate[],
     *   StreamSpecification?: StreamSpecification|array,
     *   SSESpecification?: SSESpecification|array,
     *   ReplicaUpdates?: ReplicationGroupUpdate[],
     *   @region?: string,
     * }|UpdateTableInput $input
     */
    public function updateTable($input): UpdateTableOutput
    {
        $input = UpdateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateTable', 'region' => $input->getRegion()]));

        return new UpdateTableOutput($response);
    }

    /**
     * The `UpdateTimeToLive` method enables or disables Time to Live (TTL) for the specified table. A successful
     * `UpdateTimeToLive` call returns the current `TimeToLiveSpecification`. It can take up to one hour for the change to
     * fully process. Any additional `UpdateTimeToLive` calls for the same table during this one hour duration result in a
     * `ValidationException`.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updatetimetolive
     *
     * @param array{
     *   TableName: string,
     *   TimeToLiveSpecification: TimeToLiveSpecification|array,
     *   @region?: string,
     * }|UpdateTimeToLiveInput $input
     */
    public function updateTimeToLive($input): UpdateTimeToLiveOutput
    {
        $input = UpdateTimeToLiveInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateTimeToLive', 'region' => $input->getRegion()]));

        return new UpdateTimeToLiveOutput($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://dynamodb.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://dynamodb.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://dynamodb.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://dynamodb.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'local':
                return [
                    'endpoint' => 'http://localhost:8000',
                    'signRegion' => 'us-east-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://dynamodb.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://dynamodb.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://dynamodb.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "DynamoDb".', $region));
    }
}
