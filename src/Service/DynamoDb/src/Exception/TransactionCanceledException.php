<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\CancellationReason;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The entire transaction request was canceled.
 * DynamoDB cancels a `TransactWriteItems` request under the following circumstances:.
 *
 * - A condition in one of the condition expressions is not met.
 * - A table in the `TransactWriteItems` request is in a different account or region.
 * - More than one action in the `TransactWriteItems` operation targets the same item.
 * - There is insufficient provisioned capacity for the transaction to be completed.
 * - An item size becomes too large (larger than 400 KB), or a local secondary index (LSI) becomes too large, or a
 *   similar validation error occurs because of changes made by the transaction.
 * - There is a user error, such as an invalid data format.
 *
 * DynamoDB cancels a `TransactGetItems` request under the following circumstances:
 *
 * - There is an ongoing `TransactGetItems` operation that conflicts with a concurrent `PutItem`, `UpdateItem`,
 *   `DeleteItem` or `TransactWriteItems` request. In this case the `TransactGetItems` operation fails with a
 *   `TransactionCanceledException`.
 * - A table in the `TransactGetItems` request is in a different account or region.
 * - There is insufficient provisioned capacity for the transaction to be completed.
 * - There is a user error, such as an invalid data format.
 *
 * > If using Java, DynamoDB lists the cancellation reasons on the `CancellationReasons` property. This property is not
 * > set for other languages. Transaction cancellation reasons are ordered in the order of requested items, if an item
 * > has no error it will have `None` code and `Null` message.
 *
 * Cancellation reason codes and possible error messages:
 *
 * - No Errors:
 *
 *   - Code: `None`
 *   - Message: `null`
 *
 * - Conditional Check Failed:
 *
 *   - Code: `ConditionalCheckFailed`
 *   - Message: The conditional request failed.
 *
 * - Item Collection Size Limit Exceeded:
 *
 *   - Code: `ItemCollectionSizeLimitExceeded`
 *   - Message: Collection size exceeded.
 *
 * - Transaction Conflict:
 *
 *   - Code: `TransactionConflict`
 *   - Message: Transaction is ongoing for the item.
 *
 * - Provisioned Throughput Exceeded:
 *
 *   - Code: `ProvisionedThroughputExceeded`
 *   - Messages:
 *
 *     - The level of configured provisioned throughput for the table was exceeded. Consider increasing your
 *       provisioning level with the UpdateTable API.
 *
 *       > This Message is received when provisioned throughput is exceeded is on a provisioned DynamoDB table.
 *
 *     - The level of configured provisioned throughput for one or more global secondary indexes of the table was
 *       exceeded. Consider increasing your provisioning level for the under-provisioned global secondary indexes with
 *       the UpdateTable API.
 *
 *       > This message is returned when provisioned throughput is exceeded is on a provisioned GSI.
 *
 *
 *
 * - Throttling Error:
 *
 *   - Code: `ThrottlingError`
 *   - Messages:
 *
 *     - Throughput exceeds the current capacity of your table or index. DynamoDB is automatically scaling your table or
 *       index so please try again shortly. If exceptions persist, check if you have a hot key:
 *       https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/bp-partition-key-design.html.
 *
 *       > This message is returned when writes get throttled on an On-Demand table as DynamoDB is automatically scaling
 *       > the table.
 *
 *     - Throughput exceeds the current capacity for one or more global secondary indexes. DynamoDB is automatically
 *       scaling your index so please try again shortly.
 *
 *       > This message is returned when writes get throttled on an On-Demand GSI as DynamoDB is automatically scaling
 *       > the GSI.
 *
 *
 *
 * - Validation Error:
 *
 *   - Code: `ValidationError`
 *   - Messages:
 *
 *     - One or more parameter values were invalid.
 *     - The update expression attempted to update the secondary index key beyond allowed size limits.
 *     - The update expression attempted to update the secondary index key to unsupported type.
 *     - An operand in the update expression has an incorrect data type.
 *     - Item size to update has exceeded the maximum allowed size.
 *     - Number overflow. Attempting to store a number with magnitude larger than supported range.
 *     - Type mismatch for attribute to update.
 *     - Nesting Levels have exceeded supported limits.
 *     - The document path provided in the update expression is invalid for update.
 *     - The provided expression refers to an attribute that does not exist in the item.
 */
final class TransactionCanceledException extends ClientException
{
    /**
     * A list of cancellation reasons.
     */
    private $cancellationReasons;

    /**
     * @return CancellationReason[]
     */
    public function getCancellationReasons(): array
    {
        return $this->cancellationReasons;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->cancellationReasons = empty($data['CancellationReasons']) ? [] : $this->populateResultCancellationReasonList($data['CancellationReasons']);
    }

    /**
     * @return array<string, AttributeValue>
     */
    private function populateResultAttributeMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = AttributeValue::create($value);
        }

        return $items;
    }

    private function populateResultCancellationReason(array $json): CancellationReason
    {
        return new CancellationReason([
            'Item' => !isset($json['Item']) ? null : $this->populateResultAttributeMap($json['Item']),
            'Code' => isset($json['Code']) ? (string) $json['Code'] : null,
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return CancellationReason[]
     */
    private function populateResultCancellationReasonList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultCancellationReason($item);
        }

        return $items;
    }
}
