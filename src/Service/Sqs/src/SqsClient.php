<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;
use AsyncAws\Sqs\Enum\QueueAttributeName;
use AsyncAws\Sqs\Exception\BatchEntryIdsNotDistinctException;
use AsyncAws\Sqs\Exception\BatchRequestTooLongException;
use AsyncAws\Sqs\Exception\EmptyBatchRequestException;
use AsyncAws\Sqs\Exception\InvalidAttributeNameException;
use AsyncAws\Sqs\Exception\InvalidBatchEntryIdException;
use AsyncAws\Sqs\Exception\InvalidIdFormatException;
use AsyncAws\Sqs\Exception\InvalidMessageContentsException;
use AsyncAws\Sqs\Exception\MessageNotInflightException;
use AsyncAws\Sqs\Exception\OverLimitException;
use AsyncAws\Sqs\Exception\PurgeQueueInProgressException;
use AsyncAws\Sqs\Exception\QueueDeletedRecentlyException;
use AsyncAws\Sqs\Exception\QueueDoesNotExistException;
use AsyncAws\Sqs\Exception\QueueNameExistsException;
use AsyncAws\Sqs\Exception\ReceiptHandleIsInvalidException;
use AsyncAws\Sqs\Exception\TooManyEntriesInBatchRequestException;
use AsyncAws\Sqs\Exception\UnsupportedOperationException;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityBatchRequest;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use AsyncAws\Sqs\Input\CreateQueueRequest;
use AsyncAws\Sqs\Input\DeleteMessageBatchRequest;
use AsyncAws\Sqs\Input\DeleteMessageRequest;
use AsyncAws\Sqs\Input\DeleteQueueRequest;
use AsyncAws\Sqs\Input\GetQueueAttributesRequest;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\ListQueuesRequest;
use AsyncAws\Sqs\Input\PurgeQueueRequest;
use AsyncAws\Sqs\Input\ReceiveMessageRequest;
use AsyncAws\Sqs\Input\SendMessageBatchRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\Result\ChangeMessageVisibilityBatchResult;
use AsyncAws\Sqs\Result\CreateQueueResult;
use AsyncAws\Sqs\Result\DeleteMessageBatchResult;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use AsyncAws\Sqs\Result\ListQueuesResult;
use AsyncAws\Sqs\Result\QueueExistsWaiter;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use AsyncAws\Sqs\Result\SendMessageBatchResult;
use AsyncAws\Sqs\Result\SendMessageResult;
use AsyncAws\Sqs\ValueObject\ChangeMessageVisibilityBatchRequestEntry;
use AsyncAws\Sqs\ValueObject\DeleteMessageBatchRequestEntry;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;
use AsyncAws\Sqs\ValueObject\MessageSystemAttributeValue;
use AsyncAws\Sqs\ValueObject\SendMessageBatchRequestEntry;

class SqsClient extends AbstractApi
{
    /**
     * Changes the visibility timeout of a specified message in a queue to a new value. The default visibility timeout for a
     * message is 30 seconds. The minimum is 0 seconds. The maximum is 12 hours. For more information, see Visibility
     * Timeout [^1] in the *Amazon SQS Developer Guide*.
     *
     * For example, if the default timeout for a queue is 60 seconds, 15 seconds have elapsed since you received the
     * message, and you send a ChangeMessageVisibility call with `VisibilityTimeout` set to 10 seconds, the 10 seconds begin
     * to count from the time that you make the `ChangeMessageVisibility` call. Thus, any attempt to change the visibility
     * timeout or to delete that message 10 seconds after you initially change the visibility timeout (a total of 25
     * seconds) might result in an error.
     *
     * An Amazon SQS message has three basic states:
     *
     * 1. Sent to a queue by a producer.
     * 2. Received from the queue by a consumer.
     * 3. Deleted from the queue.
     *
     * A message is considered to be *stored* after it is sent to a queue by a producer, but not yet received from the queue
     * by a consumer (that is, between states 1 and 2). There is no limit to the number of stored messages. A message is
     * considered to be *in flight* after it is received from a queue by a consumer, but not yet deleted from the queue
     * (that is, between states 2 and 3). There is a limit to the number of in flight messages.
     *
     * Limits that apply to in flight messages are unrelated to the *unlimited* number of stored messages.
     *
     * For most standard queues (depending on queue traffic and message backlog), there can be a maximum of approximately
     * 120,000 in flight messages (received from a queue by a consumer, but not yet deleted from the queue). If you reach
     * this limit, Amazon SQS returns the `OverLimit` error message. To avoid reaching the limit, you should delete messages
     * from the queue after they're processed. You can also increase the number of queues you use to process your messages.
     * To request a limit increase, file a support request [^2].
     *
     * For FIFO queues, there can be a maximum of 20,000 in flight messages (received from a queue by a consumer, but not
     * yet deleted from the queue). If you reach this limit, Amazon SQS returns no error messages.
     *
     * ! If you attempt to set the `VisibilityTimeout` to a value greater than the maximum time left, Amazon SQS returns an
     * ! error. Amazon SQS doesn't automatically recalculate and increase the timeout to the maximum remaining time.
     * !
     * ! Unlike with a queue, when you change the visibility timeout for a specific message the timeout value is applied
     * ! immediately but isn't saved in memory for that message. If you don't delete a message after it is received, the
     * ! visibility timeout for the message reverts to the original timeout value (not to the value you set using the
     * ! `ChangeMessageVisibility` action) the next time the message is received.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     * [^2]: https://console.aws.amazon.com/support/home#/case/create?issueType=service-limit-increase&amp;limitType=service-code-sqs
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibility.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#changemessagevisibility
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout: int,
     *   '@region'?: string|null,
     * }|ChangeMessageVisibilityRequest $input
     *
     * @throws MessageNotInflightException
     * @throws ReceiptHandleIsInvalidException
     */
    public function changeMessageVisibility($input): Result
    {
        $input = ChangeMessageVisibilityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeMessageVisibility', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.MessageNotInflight' => MessageNotInflightException::class,
            'ReceiptHandleIsInvalid' => ReceiptHandleIsInvalidException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Changes the visibility timeout of multiple messages. This is a batch version of `ChangeMessageVisibility.` The result
     * of the action on each message is reported individually in the response. You can send up to 10
     * `ChangeMessageVisibility` requests with each `ChangeMessageVisibilityBatch` action.
     *
     * ! Because the batch request can result in a combination of successful and unsuccessful actions, you should check for
     * ! batch errors even when the call returns an HTTP status code of `200`.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibilityBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#changemessagevisibilitybatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: array<ChangeMessageVisibilityBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * }|ChangeMessageVisibilityBatchRequest $input
     *
     * @throws TooManyEntriesInBatchRequestException
     * @throws EmptyBatchRequestException
     * @throws BatchEntryIdsNotDistinctException
     * @throws InvalidBatchEntryIdException
     */
    public function changeMessageVisibilityBatch($input): ChangeMessageVisibilityBatchResult
    {
        $input = ChangeMessageVisibilityBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeMessageVisibilityBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'AWS.SimpleQueueService.EmptyBatchRequest' => EmptyBatchRequestException::class,
            'AWS.SimpleQueueService.BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'AWS.SimpleQueueService.InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
        ]]));

        return new ChangeMessageVisibilityBatchResult($response);
    }

    /**
     * Creates a new standard or FIFO queue. You can pass one or more attributes in the request. Keep the following in mind:.
     *
     * - If you don't specify the `FifoQueue` attribute, Amazon SQS creates a standard queue.
     *
     *   > You can't change the queue type after you create it and you can't convert an existing standard queue into a FIFO
     *   > queue. You must either create a new FIFO queue for your application or delete your existing standard queue and
     *   > recreate it as a FIFO queue. For more information, see Moving From a Standard Queue to a FIFO Queue [^1] in the
     *   > *Amazon SQS Developer Guide*.
     *
     * - If you don't provide a value for an attribute, the queue is created with the default value for the attribute.
     * - If you delete a queue, you must wait at least 60 seconds before creating a queue with the same name.
     *
     * To successfully create a new queue, you must provide a queue name that adheres to the limits related to queues [^2]
     * and is unique within the scope of your queues.
     *
     * > After you create a queue, you must wait at least one second after the queue is created to be able to use the queue.
     *
     * To get the queue URL, use the `GetQueueUrl` action. `GetQueueUrl` requires only the `QueueName` parameter. be aware
     * of existing queue names:
     *
     * - If you provide the name of an existing queue along with the exact names and values of all the queue's attributes,
     *   `CreateQueue` returns the queue URL for the existing queue.
     * - If the queue name, attribute names, or attribute values don't match an existing queue, `CreateQueue` returns an
     *   error.
     *
     * > Cross-account permissions don't apply to this action. For more information, see Grant cross-account permissions to
     * > a role and a username [^3] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html#FIFO-queues-moving
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/limits-queues.html
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-customer-managed-policy-examples.html#grant-cross-account-permissions-to-role-and-user-name
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
     *
     * @param array{
     *   QueueName: string,
     *   Attributes?: array<QueueAttributeName::*, string>,
     *   tags?: array<string, string>,
     *   '@region'?: string|null,
     * }|CreateQueueRequest $input
     *
     * @throws QueueDeletedRecentlyException
     * @throws QueueNameExistsException
     */
    public function createQueue($input): CreateQueueResult
    {
        $input = CreateQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateQueue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.QueueDeletedRecently' => QueueDeletedRecentlyException::class,
            'QueueAlreadyExists' => QueueNameExistsException::class,
        ]]));

        return new CreateQueueResult($response);
    }

    /**
     * Deletes the specified message from the specified queue. To select the message to delete, use the `ReceiptHandle` of
     * the message (*not* the `MessageId` which you receive when you send the message). Amazon SQS can delete a message from
     * a queue even if a visibility timeout setting causes the message to be locked by another consumer. Amazon SQS
     * automatically deletes messages left in a queue longer than the retention period configured for the queue.
     *
     * > The `ReceiptHandle` is associated with a *specific instance* of receiving a message. If you receive a message more
     * > than once, the `ReceiptHandle` is different each time you receive a message. When you use the `DeleteMessage`
     * > action, you must provide the most recently received `ReceiptHandle` for the message (otherwise, the request
     * > succeeds, but the message will not be deleted).
     * >
     * > For standard queues, it is possible to receive a message even after you delete it. This might happen on rare
     * > occasions if one of the servers which stores a copy of the message is unavailable when you send the request to
     * > delete the message. The copy remains on the server and might be returned to you during a subsequent receive
     * > request. You should ensure that your application is idempotent, so that receiving a message more than once does not
     * > cause issues.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   '@region'?: string|null,
     * }|DeleteMessageRequest $input
     *
     * @throws InvalidIdFormatException
     * @throws ReceiptHandleIsInvalidException
     */
    public function deleteMessage($input): Result
    {
        $input = DeleteMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidIdFormat' => InvalidIdFormatException::class,
            'ReceiptHandleIsInvalid' => ReceiptHandleIsInvalidException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes up to ten messages from the specified queue. This is a batch version of `DeleteMessage.` The result of the
     * action on each message is reported individually in the response.
     *
     * ! Because the batch request can result in a combination of successful and unsuccessful actions, you should check for
     * ! batch errors even when the call returns an HTTP status code of `200`.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessageBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessagebatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: array<DeleteMessageBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * }|DeleteMessageBatchRequest $input
     *
     * @throws TooManyEntriesInBatchRequestException
     * @throws EmptyBatchRequestException
     * @throws BatchEntryIdsNotDistinctException
     * @throws InvalidBatchEntryIdException
     */
    public function deleteMessageBatch($input): DeleteMessageBatchResult
    {
        $input = DeleteMessageBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteMessageBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'AWS.SimpleQueueService.EmptyBatchRequest' => EmptyBatchRequestException::class,
            'AWS.SimpleQueueService.BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'AWS.SimpleQueueService.InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
        ]]));

        return new DeleteMessageBatchResult($response);
    }

    /**
     * Deletes the queue specified by the `QueueUrl`, regardless of the queue's contents.
     *
     * ! Be careful with the `DeleteQueue` action: When you delete a queue, any messages in the queue are no longer
     * ! available.
     *
     * When you delete a queue, the deletion process takes up to 60 seconds. Requests you send involving that queue during
     * the 60 seconds might succeed. For example, a `SendMessage` request might succeed, but after 60 seconds the queue and
     * the message you sent no longer exist.
     *
     * When you delete a queue, you must wait at least 60 seconds before creating a queue with the same name.
     *
     * > Cross-account permissions don't apply to this action. For more information, see Grant cross-account permissions to
     * > a role and a username [^1] in the *Amazon SQS Developer Guide*.
     * >
     * > The delete operation uses the HTTP `GET` verb.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-customer-managed-policy-examples.html#grant-cross-account-permissions-to-role-and-user-name
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletequeue
     *
     * @param array{
     *   QueueUrl: string,
     *   '@region'?: string|null,
     * }|DeleteQueueRequest $input
     */
    public function deleteQueue($input): Result
    {
        $input = DeleteQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteQueue', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Gets attributes for the specified queue.
     *
     * > To determine whether a queue is FIFO [^1], you can check whether `QueueName` ends with the `.fifo` suffix.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueattributes
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: array<QueueAttributeName::*>,
     *   '@region'?: string|null,
     * }|GetQueueAttributesRequest $input
     *
     * @throws InvalidAttributeNameException
     */
    public function getQueueAttributes($input): GetQueueAttributesResult
    {
        $input = GetQueueAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueAttributes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAttributeName' => InvalidAttributeNameException::class,
        ]]));

        return new GetQueueAttributesResult($response);
    }

    /**
     * Returns the URL of an existing Amazon SQS queue.
     *
     * To access a queue that belongs to another AWS account, use the `QueueOwnerAWSAccountId` parameter to specify the
     * account ID of the queue's owner. The queue's owner must grant you permission to access the queue. For more
     * information about shared queue access, see `AddPermission` or see Allow Developers to Write Messages to a Shared
     * Queue [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-writing-an-sqs-policy.html#write-messages-to-shared-queue
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: string,
     *   '@region'?: string|null,
     * }|GetQueueUrlRequest $input
     *
     * @throws QueueDoesNotExistException
     */
    public function getQueueUrl($input): GetQueueUrlResult
    {
        $input = GetQueueUrlRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueUrl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.NonExistentQueue' => QueueDoesNotExistException::class,
        ]]));

        return new GetQueueUrlResult($response);
    }

    /**
     * Returns a list of your queues in the current region. The response includes a maximum of 1,000 results. If you specify
     * a value for the optional `QueueNamePrefix` parameter, only queues with a name that begins with the specified value
     * are returned.
     *
     * The `listQueues` methods supports pagination. Set parameter `MaxResults` in the request to specify the maximum number
     * of results to be returned in the response. If you do not set `MaxResults`, the response includes a maximum of 1,000
     * results. If you set `MaxResults` and there are additional results to display, the response includes a value for
     * `NextToken`. Use `NextToken` as a parameter in your next request to `listQueues` to receive the next page of results.
     *
     * > Cross-account permissions don't apply to this action. For more information, see Grant cross-account permissions to
     * > a role and a username [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-customer-managed-policy-examples.html#grant-cross-account-permissions-to-role-and-user-name
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#listqueues
     *
     * @param array{
     *   QueueNamePrefix?: string,
     *   NextToken?: string,
     *   MaxResults?: int,
     *   '@region'?: string|null,
     * }|ListQueuesRequest $input
     */
    public function listQueues($input = []): ListQueuesResult
    {
        $input = ListQueuesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListQueues', 'region' => $input->getRegion()]));

        return new ListQueuesResult($response, $this, $input);
    }

    /**
     * Deletes the messages in a queue specified by the `QueueURL` parameter.
     *
     * ! When you use the `PurgeQueue` action, you can't retrieve any messages deleted from a queue.
     * !
     * ! The message deletion process takes up to 60 seconds. We recommend waiting for 60 seconds regardless of your queue's
     * ! size.
     *
     * Messages sent to the queue *before* you call `PurgeQueue` might be received but are deleted within the next minute.
     *
     * Messages sent to the queue *after* you call `PurgeQueue` might be deleted while the queue is being purged.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_PurgeQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#purgequeue
     *
     * @param array{
     *   QueueUrl: string,
     *   '@region'?: string|null,
     * }|PurgeQueueRequest $input
     *
     * @throws QueueDoesNotExistException
     * @throws PurgeQueueInProgressException
     */
    public function purgeQueue($input): Result
    {
        $input = PurgeQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PurgeQueue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.NonExistentQueue' => QueueDoesNotExistException::class,
            'AWS.SimpleQueueService.PurgeQueueInProgress' => PurgeQueueInProgressException::class,
        ]]));

        return new Result($response);
    }

    /**
     * @see getQueueUrl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: string,
     *   '@region'?: string|null,
     * }|GetQueueUrlRequest $input
     */
    public function queueExists($input): QueueExistsWaiter
    {
        $input = GetQueueUrlRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueUrl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.NonExistentQueue' => QueueDoesNotExistException::class,
        ]]));

        return new QueueExistsWaiter($response, $this, $input);
    }

    /**
     * Retrieves one or more messages (up to 10), from the specified queue. Using the `WaitTimeSeconds` parameter enables
     * long-poll support. For more information, see Amazon SQS Long Polling [^1] in the *Amazon SQS Developer Guide*.
     *
     * Short poll is the default behavior where a weighted random set of machines is sampled on a `ReceiveMessage` call.
     * Thus, only the messages on the sampled machines are returned. If the number of messages in the queue is small (fewer
     * than 1,000), you most likely get fewer messages than you requested per `ReceiveMessage` call. If the number of
     * messages in the queue is extremely small, you might not receive any messages in a particular `ReceiveMessage`
     * response. If this happens, repeat the request.
     *
     * For each message returned, the response includes the following:
     *
     * - The message body.
     * - An MD5 digest of the message body. For information about MD5, see RFC1321 [^2].
     * - The `MessageId` you received when you sent the message to the queue.
     * - The receipt handle.
     * - The message attributes.
     * - An MD5 digest of the message attributes.
     *
     * The receipt handle is the identifier you must provide when deleting the message. For more information, see Queue and
     * Message Identifiers [^3] in the *Amazon SQS Developer Guide*.
     *
     * You can provide the `VisibilityTimeout` parameter in your request. The parameter is applied to the messages that
     * Amazon SQS returns in the response. If you don't include the parameter, the overall visibility timeout for the queue
     * is used for the returned messages. For more information, see Visibility Timeout [^4] in the *Amazon SQS Developer
     * Guide*.
     *
     * A message that isn't deleted or a message whose visibility isn't extended before the visibility timeout expires
     * counts as a failed receive. Depending on the configuration of the queue, the message might be sent to the dead-letter
     * queue.
     *
     * > In the future, new attributes might be added. If you write code that calls this action, we recommend that you
     * > structure your code so that it can handle new attributes gracefully.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-long-polling.html
     * [^2]: https://www.ietf.org/rfc/rfc1321.txt
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-message-identifiers.html
     * [^4]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ReceiveMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: array<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     *   '@region'?: string|null,
     * }|ReceiveMessageRequest $input
     *
     * @throws OverLimitException
     */
    public function receiveMessage($input): ReceiveMessageResult
    {
        $input = ReceiveMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ReceiveMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'OverLimit' => OverLimitException::class,
        ]]));

        return new ReceiveMessageResult($response);
    }

    /**
     * Delivers a message to the specified queue.
     *
     * ! A message can include only XML, JSON, and unformatted text. The following Unicode characters are allowed:
     * !
     * ! `#x9` | `#xA` | `#xD` | `#x20` to `#xD7FF` | `#xE000` to `#xFFFD` | `#x10000` to `#x10FFFF`
     * !
     * ! Any characters not included in this list will be rejected. For more information, see the W3C specification for
     * ! characters [^1].
     *
     * [^1]: http://www.w3.org/TR/REC-xml/#charsets
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     *
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   '@region'?: string|null,
     * }|SendMessageRequest $input
     *
     * @throws InvalidMessageContentsException
     * @throws UnsupportedOperationException
     */
    public function sendMessage($input): SendMessageResult
    {
        $input = SendMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidMessageContents' => InvalidMessageContentsException::class,
            'AWS.SimpleQueueService.UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new SendMessageResult($response);
    }

    /**
     * You can use `SendMessageBatch` to send up to 10 messages to the specified queue by assigning either identical or
     * different values to each message (or by not assigning values at all). This is a batch version of `SendMessage.` For a
     * FIFO queue, multiple messages within a single batch are enqueued in the order they are sent.
     *
     * The result of sending each message is reported individually in the response. Because the batch request can result in
     * a combination of successful and unsuccessful actions, you should check for batch errors even when the call returns an
     * HTTP status code of `200`.
     *
     * The maximum allowed individual message size and the maximum total payload size (the sum of the individual lengths of
     * all of the batched messages) are both 256 KiB (262,144 bytes).
     *
     * ! A message can include only XML, JSON, and unformatted text. The following Unicode characters are allowed:
     * !
     * ! `#x9` | `#xA` | `#xD` | `#x20` to `#xD7FF` | `#xE000` to `#xFFFD` | `#x10000` to `#x10FFFF`
     * !
     * ! Any characters not included in this list will be rejected. For more information, see the W3C specification for
     * ! characters [^1].
     *
     * If you don't specify the `DelaySeconds` parameter for an entry, Amazon SQS uses the default value for the queue.
     *
     * [^1]: http://www.w3.org/TR/REC-xml/#charsets
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessagebatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: array<SendMessageBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * }|SendMessageBatchRequest $input
     *
     * @throws TooManyEntriesInBatchRequestException
     * @throws EmptyBatchRequestException
     * @throws BatchEntryIdsNotDistinctException
     * @throws BatchRequestTooLongException
     * @throws InvalidBatchEntryIdException
     * @throws UnsupportedOperationException
     */
    public function sendMessageBatch($input): SendMessageBatchResult
    {
        $input = SendMessageBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendMessageBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AWS.SimpleQueueService.TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'AWS.SimpleQueueService.EmptyBatchRequest' => EmptyBatchRequestException::class,
            'AWS.SimpleQueueService.BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'AWS.SimpleQueueService.BatchRequestTooLong' => BatchRequestTooLongException::class,
            'AWS.SimpleQueueService.InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
            'AWS.SimpleQueueService.UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new SendMessageBatchResult($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
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
                    'endpoint' => "https://sqs.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://sqs-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://sqs-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://sqs-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://sqs-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://sqs.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://sqs.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://sqs.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'sqs',
            'signVersions' => ['v4'],
        ];
    }

    protected function getServiceCode(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'sqs';
    }

    protected function getSignatureScopeName(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'sqs';
    }

    protected function getSignatureVersion(): string
    {
        @trigger_error('Using the client with an old version of Core is deprecated. Run "composer update async-aws/core".', \E_USER_DEPRECATED);

        return 'v4';
    }
}
