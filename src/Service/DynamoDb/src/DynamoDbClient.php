<?php

namespace AsyncAws\DynamoDb;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\DynamoDb\Enum\BillingMode;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\MultiRegionConsistency;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\Enum\ReturnValue;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;
use AsyncAws\DynamoDb\Enum\Select;
use AsyncAws\DynamoDb\Enum\TableClass;
use AsyncAws\DynamoDb\Exception\ConditionalCheckFailedException;
use AsyncAws\DynamoDb\Exception\DuplicateItemException;
use AsyncAws\DynamoDb\Exception\IdempotentParameterMismatchException;
use AsyncAws\DynamoDb\Exception\InternalServerErrorException;
use AsyncAws\DynamoDb\Exception\ItemCollectionSizeLimitExceededException;
use AsyncAws\DynamoDb\Exception\LimitExceededException;
use AsyncAws\DynamoDb\Exception\ProvisionedThroughputExceededException;
use AsyncAws\DynamoDb\Exception\ReplicatedWriteConflictException;
use AsyncAws\DynamoDb\Exception\RequestLimitExceededException;
use AsyncAws\DynamoDb\Exception\ResourceInUseException;
use AsyncAws\DynamoDb\Exception\ResourceNotFoundException;
use AsyncAws\DynamoDb\Exception\ThrottlingException;
use AsyncAws\DynamoDb\Exception\TransactionCanceledException;
use AsyncAws\DynamoDb\Exception\TransactionConflictException;
use AsyncAws\DynamoDb\Exception\TransactionInProgressException;
use AsyncAws\DynamoDb\Input\BatchGetItemInput;
use AsyncAws\DynamoDb\Input\BatchWriteItemInput;
use AsyncAws\DynamoDb\Input\CreateTableInput;
use AsyncAws\DynamoDb\Input\DeleteItemInput;
use AsyncAws\DynamoDb\Input\DeleteTableInput;
use AsyncAws\DynamoDb\Input\DescribeEndpointsRequest;
use AsyncAws\DynamoDb\Input\DescribeTableInput;
use AsyncAws\DynamoDb\Input\ExecuteStatementInput;
use AsyncAws\DynamoDb\Input\GetItemInput;
use AsyncAws\DynamoDb\Input\ListTablesInput;
use AsyncAws\DynamoDb\Input\PutItemInput;
use AsyncAws\DynamoDb\Input\QueryInput;
use AsyncAws\DynamoDb\Input\ScanInput;
use AsyncAws\DynamoDb\Input\TransactWriteItemsInput;
use AsyncAws\DynamoDb\Input\UpdateItemInput;
use AsyncAws\DynamoDb\Input\UpdateTableInput;
use AsyncAws\DynamoDb\Input\UpdateTimeToLiveInput;
use AsyncAws\DynamoDb\Result\BatchGetItemOutput;
use AsyncAws\DynamoDb\Result\BatchWriteItemOutput;
use AsyncAws\DynamoDb\Result\CreateTableOutput;
use AsyncAws\DynamoDb\Result\DeleteItemOutput;
use AsyncAws\DynamoDb\Result\DeleteTableOutput;
use AsyncAws\DynamoDb\Result\DescribeEndpointsResponse;
use AsyncAws\DynamoDb\Result\DescribeTableOutput;
use AsyncAws\DynamoDb\Result\ExecuteStatementOutput;
use AsyncAws\DynamoDb\Result\GetItemOutput;
use AsyncAws\DynamoDb\Result\ListTablesOutput;
use AsyncAws\DynamoDb\Result\PutItemOutput;
use AsyncAws\DynamoDb\Result\QueryOutput;
use AsyncAws\DynamoDb\Result\ScanOutput;
use AsyncAws\DynamoDb\Result\TableExistsWaiter;
use AsyncAws\DynamoDb\Result\TableNotExistsWaiter;
use AsyncAws\DynamoDb\Result\TransactWriteItemsOutput;
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
use AsyncAws\DynamoDb\ValueObject\GlobalTableWitnessGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;
use AsyncAws\DynamoDb\ValueObject\KeySchemaElement;
use AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndex;
use AsyncAws\DynamoDb\ValueObject\OnDemandThroughput;
use AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput;
use AsyncAws\DynamoDb\ValueObject\ReplicationGroupUpdate;
use AsyncAws\DynamoDb\ValueObject\SSESpecification;
use AsyncAws\DynamoDb\ValueObject\StreamSpecification;
use AsyncAws\DynamoDb\ValueObject\Tag;
use AsyncAws\DynamoDb\ValueObject\TimeToLiveSpecification;
use AsyncAws\DynamoDb\ValueObject\TransactWriteItem;
use AsyncAws\DynamoDb\ValueObject\WarmThroughput;

class DynamoDbClient extends AbstractApi
{
    /**
     * The `BatchGetItem` operation returns the attributes of one or more items from one or more tables. You identify
     * requested items by primary key.
     *
     * A single operation can retrieve up to 16 MB of data, which can contain as many as 100 items. `BatchGetItem` returns a
     * partial result if the response size limit is exceeded, the table's provisioned throughput is exceeded, more than 1MB
     * per partition is requested, or an internal processing failure occurs. If a partial result is returned, the operation
     * returns a value for `UnprocessedKeys`. You can use this value to retry the operation starting with the next item to
     * get.
     *
     * ! If you request more than 100 items, `BatchGetItem` returns a `ValidationException` with the message "Too many items
     * ! requested for the BatchGetItem call."
     *
     * For example, if you ask to retrieve 100 items, but each individual item is 300 KB in size, the system returns 52
     * items (so as not to exceed the 16 MB limit). It also returns an appropriate `UnprocessedKeys` value so you can get
     * the next page of results. If desired, your application can include its own logic to assemble the pages of results
     * into one dataset.
     *
     * If *none* of the items can be processed due to insufficient provisioned throughput on all of the tables in the
     * request, then `BatchGetItem` returns a `ProvisionedThroughputExceededException`. If *at least one* of the items is
     * successfully processed, then `BatchGetItem` completes successfully, while returning the keys of the unread items in
     * `UnprocessedKeys`.
     *
     * ! If DynamoDB returns any unprocessed items, you should retry the batch operation on those items. However, *we
     * ! strongly recommend that you use an exponential backoff algorithm*. If you retry the batch operation immediately,
     * ! the underlying read or write requests can still fail due to throttling on the individual tables. If you delay the
     * ! batch operation using exponential backoff, the individual requests in the batch are much more likely to succeed.
     * !
     * ! For more information, see Batch Operations and Error Handling [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * By default, `BatchGetItem` performs eventually consistent reads on every table in the request. If you want strongly
     * consistent reads instead, you can set `ConsistentRead` to `true` for any or all tables.
     *
     * In order to minimize response latency, `BatchGetItem` may retrieve items in parallel.
     *
     * When designing your application, keep in mind that DynamoDB does not return items in any particular order. To help
     * parse the response by item, include the primary key values for the items in your request in the
     * `ProjectionExpression` parameter.
     *
     * If a requested item does not exist, it is not returned in the result. Requests for nonexistent items consume the
     * minimum read capacity units according to the type of read. For more information, see Working with Tables [^2] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * > `BatchGetItem` will result in a `ValidationException` if the same key is specified multiple times.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ErrorHandling.html#BatchOperations
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#CapacityUnitCalculations
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchGetItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchgetitem
     *
     * @param array{
     *   RequestItems: array<string, KeysAndAttributes|array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   '@region'?: string|null,
     * }|BatchGetItemInput $input
     *
     * @throws InternalServerErrorException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function batchGetItem($input): BatchGetItemOutput
    {
        $input = BatchGetItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchGetItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new BatchGetItemOutput($response, $this, $input);
    }

    /**
     * The `BatchWriteItem` operation puts or deletes multiple items in one or more tables. A single call to
     * `BatchWriteItem` can transmit up to 16MB of data over the network, consisting of up to 25 item put or delete
     * operations. While individual items can be up to 400 KB once stored, it's important to note that an item's
     * representation might be greater than 400KB while being sent in DynamoDB's JSON format for the API call. For more
     * details on this distinction, see Naming Rules and Data Types [^1].
     *
     * > `BatchWriteItem` cannot update items. If you perform a `BatchWriteItem` operation on an existing item, that item's
     * > values will be overwritten by the operation and it will appear like it was updated. To update items, we recommend
     * > you use the `UpdateItem` action.
     *
     * The individual `PutItem` and `DeleteItem` operations specified in `BatchWriteItem` are atomic; however
     * `BatchWriteItem` as a whole is not. If any requested operations fail because the table's provisioned throughput is
     * exceeded or an internal processing failure occurs, the failed operations are returned in the `UnprocessedItems`
     * response parameter. You can investigate and optionally resend the requests. Typically, you would call
     * `BatchWriteItem` in a loop. Each iteration would check for unprocessed items and submit a new `BatchWriteItem`
     * request with those unprocessed items until all items have been processed.
     *
     * For tables and indexes with provisioned capacity, if none of the items can be processed due to insufficient
     * provisioned throughput on all of the tables in the request, then `BatchWriteItem` returns a
     * `ProvisionedThroughputExceededException`. For all tables and indexes, if none of the items can be processed due to
     * other throttling scenarios (such as exceeding partition level limits), then `BatchWriteItem` returns a
     * `ThrottlingException`.
     *
     * ! If DynamoDB returns any unprocessed items, you should retry the batch operation on those items. However, *we
     * ! strongly recommend that you use an exponential backoff algorithm*. If you retry the batch operation immediately,
     * ! the underlying read or write requests can still fail due to throttling on the individual tables. If you delay the
     * ! batch operation using exponential backoff, the individual requests in the batch are much more likely to succeed.
     * !
     * ! For more information, see Batch Operations and Error Handling [^2] in the *Amazon DynamoDB Developer Guide*.
     *
     * With `BatchWriteItem`, you can efficiently write or delete large amounts of data, such as from Amazon EMR, or copy
     * data from another database into DynamoDB. In order to improve performance with these large-scale operations,
     * `BatchWriteItem` does not behave in the same way as individual `PutItem` and `DeleteItem` calls would. For example,
     * you cannot specify conditions on individual put and delete requests, and `BatchWriteItem` does not return deleted
     * items in the response.
     *
     * If you use a programming language that supports concurrency, you can use threads to write items in parallel. Your
     * application must include the necessary logic to manage the threads. With languages that don't support threading, you
     * must update or delete the specified items one at a time. In both situations, `BatchWriteItem` performs the specified
     * put and delete operations in parallel, giving you the power of the thread pool approach without having to introduce
     * complexity into your application.
     *
     * Parallel processing reduces latency, but each specified put and delete request consumes the same number of write
     * capacity units whether it is processed in parallel or not. Delete operations on nonexistent items consume one write
     * capacity unit.
     *
     * If one or more of the following is true, DynamoDB rejects the entire batch write operation:
     *
     * - One or more tables specified in the `BatchWriteItem` request does not exist.
     * - Primary key attributes specified on an item in the request do not match those in the corresponding table's primary
     *   key schema.
     * - You try to perform multiple operations on the same item in the same `BatchWriteItem` request. For example, you
     *   cannot put and delete the same item in the same `BatchWriteItem` request.
     * - Your request contains at least two items with identical hash and range keys (which essentially is two put
     *   operations).
     * - There are more than 25 requests in the batch.
     * - Any individual item in a batch exceeds 400 KB.
     * - The total request size exceeds 16 MB.
     * - Any individual items with keys exceeding the key length limits. For a partition key, the limit is 2048 bytes and
     *   for a sort key, the limit is 1024 bytes.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.NamingRulesDataTypes.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ErrorHandling.html#Programming.Errors.BatchOperations
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_BatchWriteItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#batchwriteitem
     *
     * @param array{
     *   RequestItems: array<string, array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   '@region'?: string|null,
     * }|BatchWriteItemInput $input
     *
     * @throws InternalServerErrorException
     * @throws ItemCollectionSizeLimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws ReplicatedWriteConflictException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function batchWriteItem($input): BatchWriteItemOutput
    {
        $input = BatchWriteItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchWriteItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ItemCollectionSizeLimitExceededException' => ItemCollectionSizeLimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ReplicatedWriteConflictException' => ReplicatedWriteConflictException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new BatchWriteItemOutput($response);
    }

    /**
     * The `CreateTable` operation adds a new table to your account. In an Amazon Web Services account, table names must be
     * unique within each Region. That is, you can have two tables with same name if you create the tables in different
     * Regions.
     *
     * `CreateTable` is an asynchronous operation. Upon receiving a `CreateTable` request, DynamoDB immediately returns a
     * response with a `TableStatus` of `CREATING`. After the table is created, DynamoDB sets the `TableStatus` to `ACTIVE`.
     * You can perform read and write operations only on an `ACTIVE` table.
     *
     * You can optionally define secondary indexes on the new table, as part of the `CreateTable` operation. If you want to
     * create multiple tables with secondary indexes on them, you must create the tables sequentially. Only one table with
     * secondary indexes can be in the `CREATING` state at any given time.
     *
     * You can use the `DescribeTable` action to check the table status.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_CreateTable.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#createtable
     *
     * @param array{
     *   AttributeDefinitions: array<AttributeDefinition|array>,
     *   TableName: string,
     *   KeySchema: array<KeySchemaElement|array>,
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
     *
     * @throws InternalServerErrorException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     */
    public function createTable($input): CreateTableOutput
    {
        $input = CreateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new CreateTableOutput($response);
    }

    /**
     * Deletes a single item in a table by primary key. You can perform a conditional delete operation that deletes the item
     * if it exists, or if it has an expected attribute value.
     *
     * In addition to deleting an item, you can also return the item's attribute values in the same operation, using the
     * `ReturnValues` parameter.
     *
     * Unless you specify conditions, the `DeleteItem` is an idempotent operation; running it multiple times on the same
     * item or attribute does *not* result in an error response.
     *
     * Conditional deletes are useful for deleting items only if specific conditions are met. If those conditions are met,
     * DynamoDB performs the delete. Otherwise, the item is not deleted.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DeleteItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deleteitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue|array>,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|DeleteItemInput $input
     *
     * @throws ConditionalCheckFailedException
     * @throws InternalServerErrorException
     * @throws ItemCollectionSizeLimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws ReplicatedWriteConflictException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws TransactionConflictException
     */
    public function deleteItem($input): DeleteItemOutput
    {
        $input = DeleteItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConditionalCheckFailedException' => ConditionalCheckFailedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ItemCollectionSizeLimitExceededException' => ItemCollectionSizeLimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ReplicatedWriteConflictException' => ReplicatedWriteConflictException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'TransactionConflictException' => TransactionConflictException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new DeleteItemOutput($response);
    }

    /**
     * The `DeleteTable` operation deletes a table and all of its items. After a `DeleteTable` request, the specified table
     * is in the `DELETING` state until DynamoDB completes the deletion. If the table is in the `ACTIVE` state, you can
     * delete it. If a table is in `CREATING` or `UPDATING` states, then DynamoDB returns a `ResourceInUseException`. If the
     * specified table does not exist, DynamoDB returns a `ResourceNotFoundException`. If table is already in the `DELETING`
     * state, no error is returned.
     *
     * > DynamoDB might continue to accept data read and write operations, such as `GetItem` and `PutItem`, on a table in
     * > the `DELETING` state until the table deletion is complete. For the full list of table states, see TableStatus [^1].
     *
     * When you delete a table, any indexes on that table are also deleted.
     *
     * If you have DynamoDB Streams enabled on the table, then the corresponding stream on that table goes into the
     * `DISABLED` state, and the stream is automatically deleted after 24 hours.
     *
     * Use the `DescribeTable` action to check the status of the table.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_TableDescription.html#DDB-Type-TableDescription-TableStatus
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DeleteTable.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#deletetable
     *
     * @param array{
     *   TableName: string,
     *   '@region'?: string|null,
     * }|DeleteTableInput $input
     *
     * @throws InternalServerErrorException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function deleteTable($input): DeleteTableOutput
    {
        $input = DeleteTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new DeleteTableOutput($response);
    }

    /**
     * Returns the regional endpoint information. For more information on policy permissions, please see Internetwork
     * traffic privacy [^1].
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/inter-network-traffic-privacy.html#inter-network-traffic-DescribeEndpoints
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DescribeEndpoints.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describeendpoints
     *
     * @param array{
     *   '@region'?: string|null,
     * }|DescribeEndpointsRequest $input
     */
    public function describeEndpoints($input = []): DescribeEndpointsResponse
    {
        $input = DescribeEndpointsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeEndpoints', 'region' => $input->getRegion()]));

        return new DescribeEndpointsResponse($response);
    }

    /**
     * Returns information about the table, including the current status of the table, when it was created, the primary key
     * schema, and any indexes on the table.
     *
     * > If you issue a `DescribeTable` request immediately after a `CreateTable` request, DynamoDB might return a
     * > `ResourceNotFoundException`. This is because `DescribeTable` uses an eventually consistent query, and the metadata
     * > for your table might not be available at that moment. Wait for a few seconds, and then try the `DescribeTable`
     * > request again.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_DescribeTable.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#describetable
     *
     * @param array{
     *   TableName: string,
     *   '@region'?: string|null,
     * }|DescribeTableInput $input
     *
     * @throws InternalServerErrorException
     * @throws ResourceNotFoundException
     */
    public function describeTable($input): DescribeTableOutput
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new DescribeTableOutput($response);
    }

    /**
     * This operation allows you to perform reads and singleton writes on data stored in DynamoDB, using PartiQL.
     *
     * For PartiQL reads (`SELECT` statement), if the total number of processed items exceeds the maximum dataset size limit
     * of 1 MB, the read stops and results are returned to the user as a `LastEvaluatedKey` value to continue the read in a
     * subsequent operation. If the filter criteria in `WHERE` clause does not match any data, the read will return an empty
     * result set.
     *
     * A single `SELECT` statement response can return up to the maximum number of items (if using the Limit parameter) or a
     * maximum of 1 MB of data (and then apply any filtering to the results using `WHERE` clause). If `LastEvaluatedKey` is
     * present in the response, you need to paginate the result set. If `NextToken` is present, you need to paginate the
     * result set and include `NextToken`.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ExecuteStatement.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#executestatement
     *
     * @param array{
     *   Statement: string,
     *   Parameters?: array<AttributeValue|array>|null,
     *   ConsistentRead?: bool|null,
     *   NextToken?: string|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   Limit?: int|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|ExecuteStatementInput $input
     *
     * @throws ConditionalCheckFailedException
     * @throws DuplicateItemException
     * @throws InternalServerErrorException
     * @throws ItemCollectionSizeLimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws TransactionConflictException
     */
    public function executeStatement($input): ExecuteStatementOutput
    {
        $input = ExecuteStatementInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ExecuteStatement', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConditionalCheckFailedException' => ConditionalCheckFailedException::class,
            'DuplicateItemException' => DuplicateItemException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ItemCollectionSizeLimitExceededException' => ItemCollectionSizeLimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'TransactionConflictException' => TransactionConflictException::class,
        ]]));

        return new ExecuteStatementOutput($response);
    }

    /**
     * The `GetItem` operation returns a set of attributes for the item with the given primary key. If there is no matching
     * item, `GetItem` does not return any data and there will be no `Item` element in the response.
     *
     * `GetItem` provides an eventually consistent read by default. If your application requires a strongly consistent read,
     * set `ConsistentRead` to `true`. Although a strongly consistent read might take more time than an eventually
     * consistent read, it always returns the last updated value.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_GetItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#getitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue|array>,
     *   AttributesToGet?: string[]|null,
     *   ConsistentRead?: bool|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|GetItemInput $input
     *
     * @throws InternalServerErrorException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function getItem($input): GetItemOutput
    {
        $input = GetItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new GetItemOutput($response);
    }

    /**
     * Returns an array of table names associated with the current account and endpoint. The output from `ListTables` is
     * paginated, with each page returning a maximum of 100 table names.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_ListTables.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#listtables
     *
     * @param array{
     *   ExclusiveStartTableName?: string|null,
     *   Limit?: int|null,
     *   '@region'?: string|null,
     * }|ListTablesInput $input
     *
     * @throws InternalServerErrorException
     */
    public function listTables($input = []): ListTablesOutput
    {
        $input = ListTablesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTables', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new ListTablesOutput($response, $this, $input);
    }

    /**
     * Creates a new item, or replaces an old item with a new item. If an item that has the same primary key as the new item
     * already exists in the specified table, the new item completely replaces the existing item. You can perform a
     * conditional put operation (add a new item if one with the specified primary key doesn't exist), or replace an
     * existing item if it has certain attribute values. You can return the item's attribute values in the same operation,
     * using the `ReturnValues` parameter.
     *
     * When you add an item, the primary key attributes are the only required attributes.
     *
     * Empty String and Binary attribute values are allowed. Attribute values of type String and Binary must have a length
     * greater than zero if the attribute is used as a key attribute for a table or index. Set type attributes cannot be
     * empty.
     *
     * Invalid Requests with empty values will be rejected with a `ValidationException` exception.
     *
     * > To prevent a new item from replacing an existing item, use a conditional expression that contains the
     * > `attribute_not_exists` function with the name of the attribute being used as the partition key for the table. Since
     * > every record must contain that attribute, the `attribute_not_exists` function will only succeed if no matching item
     * > exists.
     *
     * For more information about `PutItem`, see Working with Items [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithItems.html
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_PutItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#putitem
     *
     * @param array{
     *   TableName: string,
     *   Item: array<string, AttributeValue|array>,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|PutItemInput $input
     *
     * @throws ConditionalCheckFailedException
     * @throws InternalServerErrorException
     * @throws ItemCollectionSizeLimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws ReplicatedWriteConflictException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws TransactionConflictException
     */
    public function putItem($input): PutItemOutput
    {
        $input = PutItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConditionalCheckFailedException' => ConditionalCheckFailedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ItemCollectionSizeLimitExceededException' => ItemCollectionSizeLimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ReplicatedWriteConflictException' => ReplicatedWriteConflictException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'TransactionConflictException' => TransactionConflictException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new PutItemOutput($response);
    }

    /**
     * You must provide the name of the partition key attribute and a single value for that attribute. `Query` returns all
     * items with that partition key value. Optionally, you can provide a sort key attribute and use a comparison operator
     * to refine the search results.
     *
     * Use the `KeyConditionExpression` parameter to provide a specific value for the partition key. The `Query` operation
     * will return all of the items from the table or index with that partition key value. You can optionally narrow the
     * scope of the `Query` operation by specifying a sort key value and a comparison operator in `KeyConditionExpression`.
     * To further refine the `Query` results, you can optionally provide a `FilterExpression`. A `FilterExpression`
     * determines which items within the results should be returned to you. All of the other results are discarded.
     *
     * A `Query` operation always returns a result set. If no matching items are found, the result set will be empty.
     * Queries that do not return results consume the minimum number of read capacity units for that type of read operation.
     *
     * > DynamoDB calculates the number of read capacity units consumed based on item size, not on the amount of data that
     * > is returned to an application. The number of capacity units consumed will be the same whether you request all of
     * > the attributes (the default behavior) or just some of them (using a projection expression). The number will also be
     * > the same whether or not you use a `FilterExpression`.
     *
     * `Query` results are always sorted by the sort key value. If the data type of the sort key is Number, the results are
     * returned in numeric order; otherwise, the results are returned in order of UTF-8 bytes. By default, the sort order is
     * ascending. To reverse the order, set the `ScanIndexForward` parameter to false.
     *
     * A single `Query` operation will read up to the maximum number of items set (if using the `Limit` parameter) or a
     * maximum of 1 MB of data and then apply any filtering to the results using `FilterExpression`. If `LastEvaluatedKey`
     * is present in the response, you will need to paginate the result set. For more information, see Paginating the
     * Results [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * `FilterExpression` is applied after a `Query` finishes, but before the results are returned. A `FilterExpression`
     * cannot contain partition key or sort key attributes. You need to specify those attributes in the
     * `KeyConditionExpression`.
     *
     * > A `Query` operation can return an empty result set and a `LastEvaluatedKey` if all the items read for the page of
     * > results are filtered out.
     *
     * You can query a table, a local secondary index, or a global secondary index. For a query on a table or on a local
     * secondary index, you can set the `ConsistentRead` parameter to `true` and obtain a strongly consistent result. Global
     * secondary indexes support eventually consistent reads only, so do not specify `ConsistentRead` when querying a global
     * secondary index.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Query.html#Query.Pagination
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_Query.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#query
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string|null,
     *   Select?: Select::*|null,
     *   AttributesToGet?: string[]|null,
     *   Limit?: int|null,
     *   ConsistentRead?: bool|null,
     *   KeyConditions?: array<string, Condition|array>|null,
     *   QueryFilter?: array<string, Condition|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ScanIndexForward?: bool|null,
     *   ExclusiveStartKey?: array<string, AttributeValue|array>|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   FilterExpression?: string|null,
     *   KeyConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   '@region'?: string|null,
     * }|QueryInput $input
     *
     * @throws InternalServerErrorException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function query($input): QueryOutput
    {
        $input = QueryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Query', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new QueryOutput($response, $this, $input);
    }

    /**
     * The `Scan` operation returns one or more items and item attributes by accessing every item in a table or a secondary
     * index. To have DynamoDB return fewer items, you can provide a `FilterExpression` operation.
     *
     * If the total size of scanned items exceeds the maximum dataset size limit of 1 MB, the scan completes and results are
     * returned to the user. The `LastEvaluatedKey` value is also returned and the requestor can use the `LastEvaluatedKey`
     * to continue the scan in a subsequent operation. Each scan response also includes number of items that were scanned
     * (ScannedCount) as part of the request. If using a `FilterExpression`, a scan result can result in no items meeting
     * the criteria and the `Count` will result in zero. If you did not use a `FilterExpression` in the scan request, then
     * `Count` is the same as `ScannedCount`.
     *
     * > `Count` and `ScannedCount` only return the count of items specific to a single scan request and, unless the table
     * > is less than 1MB, do not represent the total number of items in the table.
     *
     * A single `Scan` operation first reads up to the maximum number of items set (if using the `Limit` parameter) or a
     * maximum of 1 MB of data and then applies any filtering to the results if a `FilterExpression` is provided. If
     * `LastEvaluatedKey` is present in the response, pagination is required to complete the full table scan. For more
     * information, see Paginating the Results [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * `Scan` operations proceed sequentially; however, for faster performance on a large table or secondary index,
     * applications can request a parallel `Scan` operation by providing the `Segment` and `TotalSegments` parameters. For
     * more information, see Parallel Scan [^2] in the *Amazon DynamoDB Developer Guide*.
     *
     * By default, a `Scan` uses eventually consistent reads when accessing the items in a table. Therefore, the results
     * from an eventually consistent `Scan` may not include the latest item changes at the time the scan iterates through
     * each item in the table. If you require a strongly consistent read of each item as the scan iterates through the items
     * in the table, you can set the `ConsistentRead` parameter to true. Strong consistency only relates to the consistency
     * of the read at the item level.
     *
     * > DynamoDB does not provide snapshot isolation for a scan operation when the `ConsistentRead` parameter is set to
     * > true. Thus, a DynamoDB scan operation does not guarantee that all reads in a scan see a consistent snapshot of the
     * > table when the scan operation was requested.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Scan.html#Scan.Pagination
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Scan.html#Scan.ParallelScan
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_Scan.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#scan
     *
     * @param array{
     *   TableName: string,
     *   IndexName?: string|null,
     *   AttributesToGet?: string[]|null,
     *   Limit?: int|null,
     *   Select?: Select::*|null,
     *   ScanFilter?: array<string, Condition|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ExclusiveStartKey?: array<string, AttributeValue|array>|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   TotalSegments?: int|null,
     *   Segment?: int|null,
     *   ProjectionExpression?: string|null,
     *   FilterExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ConsistentRead?: bool|null,
     *   '@region'?: string|null,
     * }|ScanInput $input
     *
     * @throws InternalServerErrorException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     */
    public function scan($input): ScanOutput
    {
        $input = ScanInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Scan', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new ScanOutput($response, $this, $input);
    }

    /**
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   '@region'?: string|null,
     * }|DescribeTableInput $input
     */
    public function tableExists($input): TableExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new TableExistsWaiter($response, $this, $input);
    }

    /**
     * @see describeTable
     *
     * @param array{
     *   TableName: string,
     *   '@region'?: string|null,
     * }|DescribeTableInput $input
     */
    public function tableNotExists($input): TableNotExistsWaiter
    {
        $input = DescribeTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new TableNotExistsWaiter($response, $this, $input);
    }

    /**
     * `TransactWriteItems` is a synchronous write operation that groups up to 100 action requests. These actions can target
     * items in different tables, but not in different Amazon Web Services accounts or Regions, and no two actions can
     * target the same item. For example, you cannot both `ConditionCheck` and `Update` the same item. The aggregate size of
     * the items in the transaction cannot exceed 4 MB.
     *
     * The actions are completed atomically so that either all of them succeed, or all of them fail. They are defined by the
     * following objects:
     *
     * - `Put`  —   Initiates a `PutItem` operation to write a new item. This structure specifies the primary key of the
     *   item to be written, the name of the table to write it in, an optional condition expression that must be satisfied
     *   for the write to succeed, a list of the item's attributes, and a field indicating whether to retrieve the item's
     *   attributes if the condition is not met.
     * - `Update`  —   Initiates an `UpdateItem` operation to update an existing item. This structure specifies the
     *   primary key of the item to be updated, the name of the table where it resides, an optional condition expression
     *   that must be satisfied for the update to succeed, an expression that defines one or more attributes to be updated,
     *   and a field indicating whether to retrieve the item's attributes if the condition is not met.
     * - `Delete`  —   Initiates a `DeleteItem` operation to delete an existing item. This structure specifies the
     *   primary key of the item to be deleted, the name of the table where it resides, an optional condition expression
     *   that must be satisfied for the deletion to succeed, and a field indicating whether to retrieve the item's
     *   attributes if the condition is not met.
     * - `ConditionCheck`  —   Applies a condition to an item that is not being modified by the transaction. This
     *   structure specifies the primary key of the item to be checked, the name of the table where it resides, a condition
     *   expression that must be satisfied for the transaction to succeed, and a field indicating whether to retrieve the
     *   item's attributes if the condition is not met.
     *
     * DynamoDB rejects the entire `TransactWriteItems` request if any of the following is true:
     *
     * - A condition in one of the condition expressions is not met.
     * - An ongoing operation is in the process of updating the same item.
     * - There is insufficient provisioned capacity for the transaction to be completed.
     * - An item size becomes too large (bigger than 400 KB), a local secondary index (LSI) becomes too large, or a similar
     *   validation error occurs because of changes made by the transaction.
     * - The aggregate size of the items in the transaction exceeds 4 MB.
     * - There is a user error, such as an invalid data format.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_TransactWriteItems.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#transactwriteitems
     *
     * @param array{
     *   TransactItems: array<TransactWriteItem|array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * }|TransactWriteItemsInput $input
     *
     * @throws IdempotentParameterMismatchException
     * @throws InternalServerErrorException
     * @throws ProvisionedThroughputExceededException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws TransactionCanceledException
     * @throws TransactionInProgressException
     */
    public function transactWriteItems($input): TransactWriteItemsOutput
    {
        $input = TransactWriteItemsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'TransactWriteItems', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'IdempotentParameterMismatchException' => IdempotentParameterMismatchException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'TransactionCanceledException' => TransactionCanceledException::class,
            'TransactionInProgressException' => TransactionInProgressException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new TransactWriteItemsOutput($response);
    }

    /**
     * Edits an existing item's attributes, or adds a new item to the table if it does not already exist. You can put,
     * delete, or add attribute values. You can also perform a conditional update on an existing item (insert a new
     * attribute name-value pair if it doesn't exist, or replace an existing name-value pair if it has certain expected
     * attribute values).
     *
     * You can also return the item's attribute values in the same `UpdateItem` operation using the `ReturnValues`
     * parameter.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateItem.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updateitem
     *
     * @param array{
     *   TableName: string,
     *   Key: array<string, AttributeValue|array>,
     *   AttributeUpdates?: array<string, AttributeValueUpdate|array>|null,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   UpdateExpression?: string|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|UpdateItemInput $input
     *
     * @throws ConditionalCheckFailedException
     * @throws InternalServerErrorException
     * @throws ItemCollectionSizeLimitExceededException
     * @throws ProvisionedThroughputExceededException
     * @throws ReplicatedWriteConflictException
     * @throws RequestLimitExceededException
     * @throws ResourceNotFoundException
     * @throws ThrottlingException
     * @throws TransactionConflictException
     */
    public function updateItem($input): UpdateItemOutput
    {
        $input = UpdateItemInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateItem', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConditionalCheckFailedException' => ConditionalCheckFailedException::class,
            'InternalServerError' => InternalServerErrorException::class,
            'ItemCollectionSizeLimitExceededException' => ItemCollectionSizeLimitExceededException::class,
            'ProvisionedThroughputExceededException' => ProvisionedThroughputExceededException::class,
            'ReplicatedWriteConflictException' => ReplicatedWriteConflictException::class,
            'RequestLimitExceeded' => RequestLimitExceededException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'ThrottlingException' => ThrottlingException::class,
            'TransactionConflictException' => TransactionConflictException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new UpdateItemOutput($response);
    }

    /**
     * Modifies the provisioned throughput settings, global secondary indexes, or DynamoDB Streams settings for a given
     * table.
     *
     * You can only perform one of the following operations at once:
     *
     * - Modify the provisioned throughput settings of the table.
     * - Remove a global secondary index from the table.
     * - Create a new global secondary index on the table. After the index begins backfilling, you can use `UpdateTable` to
     *   perform other operations.
     *
     * `UpdateTable` is an asynchronous operation; while it's executing, the table status changes from `ACTIVE` to
     * `UPDATING`. While it's `UPDATING`, you can't issue another `UpdateTable` request. When the table returns to the
     * `ACTIVE` state, the `UpdateTable` operation is complete.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTable.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updatetable
     *
     * @param array{
     *   AttributeDefinitions?: array<AttributeDefinition|array>|null,
     *   TableName: string,
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
     *
     * @throws InternalServerErrorException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function updateTable($input): UpdateTableOutput
    {
        $input = UpdateTableInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateTable', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new UpdateTableOutput($response);
    }

    /**
     * The `UpdateTimeToLive` method enables or disables Time to Live (TTL) for the specified table. A successful
     * `UpdateTimeToLive` call returns the current `TimeToLiveSpecification`. It can take up to one hour for the change to
     * fully process. Any additional `UpdateTimeToLive` calls for the same table during this one hour duration result in a
     * `ValidationException`.
     *
     * TTL compares the current time in epoch time format to the time stored in the TTL attribute of an item. If the epoch
     * time value stored in the attribute is less than the current time, the item is marked as expired and subsequently
     * deleted.
     *
     * > The epoch time format is the number of seconds elapsed since 12:00:00 AM January 1, 1970 UTC.
     *
     * DynamoDB deletes expired items on a best-effort basis to ensure availability of throughput for other data operations.
     *
     * ! DynamoDB typically deletes expired items within two days of expiration. The exact duration within which an item
     * ! gets deleted after expiration is specific to the nature of the workload. Items that have expired and not been
     * ! deleted will still show up in reads, queries, and scans.
     *
     * As items are deleted, they are removed from any local secondary index and global secondary index immediately in the
     * same eventually consistent way as a standard delete operation.
     *
     * For more information, see Time To Live [^1] in the Amazon DynamoDB Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/TTL.html
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/APIReference/API_UpdateTimeToLive.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-dynamodb-2012-08-10.html#updatetimetolive
     *
     * @param array{
     *   TableName: string,
     *   TimeToLiveSpecification: TimeToLiveSpecification|array,
     *   '@region'?: string|null,
     * }|UpdateTimeToLiveInput $input
     *
     * @throws InternalServerErrorException
     * @throws LimitExceededException
     * @throws ResourceInUseException
     * @throws ResourceNotFoundException
     */
    public function updateTimeToLive($input): UpdateTimeToLiveOutput
    {
        $input = UpdateTimeToLiveInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateTimeToLive', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerError' => InternalServerErrorException::class,
            'LimitExceededException' => LimitExceededException::class,
            'ResourceInUseException' => ResourceInUseException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ], 'usesEndpointDiscovery' => true]));

        return new UpdateTimeToLiveOutput($response);
    }

    protected function discoverEndpoints(?string $region): array
    {
        return $this->describeEndpoints($region ? ['@region' => $region] : [])->getEndpoints();
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://dynamodb.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://dynamodb.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
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
            case 'ca-central-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'ca-west-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://dynamodb-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://dynamodb.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://dynamodb.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://dynamodb.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://dynamodb.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'dynamodb',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://dynamodb.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'dynamodb',
            'signVersions' => ['v4'],
        ];
    }
}
