<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;
use AsyncAws\Sqs\Enum\QueueAttributeName;
use AsyncAws\Sqs\Exception\BatchEntryIdsNotDistinctException;
use AsyncAws\Sqs\Exception\BatchRequestTooLongException;
use AsyncAws\Sqs\Exception\EmptyBatchRequestException;
use AsyncAws\Sqs\Exception\InvalidAddressException;
use AsyncAws\Sqs\Exception\InvalidAttributeNameException;
use AsyncAws\Sqs\Exception\InvalidAttributeValueException;
use AsyncAws\Sqs\Exception\InvalidBatchEntryIdException;
use AsyncAws\Sqs\Exception\InvalidIdFormatException;
use AsyncAws\Sqs\Exception\InvalidMessageContentsException;
use AsyncAws\Sqs\Exception\InvalidSecurityException;
use AsyncAws\Sqs\Exception\KmsAccessDeniedException;
use AsyncAws\Sqs\Exception\KmsDisabledException;
use AsyncAws\Sqs\Exception\KmsInvalidKeyUsageException;
use AsyncAws\Sqs\Exception\KmsInvalidStateException;
use AsyncAws\Sqs\Exception\KmsNotFoundException;
use AsyncAws\Sqs\Exception\KmsOptInRequiredException;
use AsyncAws\Sqs\Exception\KmsThrottledException;
use AsyncAws\Sqs\Exception\MessageNotInflightException;
use AsyncAws\Sqs\Exception\OverLimitException;
use AsyncAws\Sqs\Exception\PurgeQueueInProgressException;
use AsyncAws\Sqs\Exception\QueueDeletedRecentlyException;
use AsyncAws\Sqs\Exception\QueueDoesNotExistException;
use AsyncAws\Sqs\Exception\QueueNameExistsException;
use AsyncAws\Sqs\Exception\ReceiptHandleIsInvalidException;
use AsyncAws\Sqs\Exception\RequestThrottledException;
use AsyncAws\Sqs\Exception\TooManyEntriesInBatchRequestException;
use AsyncAws\Sqs\Exception\UnsupportedOperationException;
use AsyncAws\Sqs\Input\AddPermissionRequest;
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
     * Adds a permission to a queue for a specific principal [^1]. This allows sharing access to the queue.
     *
     * When you create a queue, you have full control access rights for the queue. Only you, the owner of the queue, can
     * grant or deny permissions to the queue. For more information about these permissions, see Allow Developers to Write
     * Messages to a Shared Queue [^2] in the *Amazon SQS Developer Guide*.
     *
     * > - `AddPermission` generates a policy for you. You can use `SetQueueAttributes` to upload your policy. For more
     * >   information, see Using Custom Policies with the Amazon SQS Access Policy Language [^3] in the *Amazon SQS
     * >   Developer Guide*.
     * > - An Amazon SQS policy can have a maximum of seven actions per statement.
     * > - To remove the ability to change queue permissions, you must deny permission to the `AddPermission`,
     * >   `RemovePermission`, and `SetQueueAttributes` actions in your IAM policy.
     * > - Amazon SQS `AddPermission` does not support adding a non-account principal.
     * >
     *
     * > Cross-account permissions don't apply to this action. For more information, see Grant cross-account permissions to
     * > a role and a username [^4] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/glos-chap.html#P
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-writing-an-sqs-policy.html#write-messages-to-shared-queue
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-creating-custom-policies.html
     * [^4]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-customer-managed-policy-examples.html#grant-cross-account-permissions-to-role-and-user-name
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_AddPermission.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#addpermission
     *
     * @param array{
     *   QueueUrl: string,
     *   Label: string,
     *   AWSAccountIds: string[],
     *   Actions: string[],
     *   '@region'?: string|null,
     * }|AddPermissionRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws OverLimitException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function addPermission($input): Result
    {
        $input = AddPermissionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddPermission', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'OverLimit' => OverLimitException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new Result($response);
    }

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
     * For FIFO queues, there can be a maximum of 120,000 in flight messages (received from a queue by a consumer, but not
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
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws MessageNotInflightException
     * @throws QueueDoesNotExistException
     * @throws ReceiptHandleIsInvalidException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function changeMessageVisibility($input): Result
    {
        $input = ChangeMessageVisibilityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeMessageVisibility', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'MessageNotInflight' => MessageNotInflightException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'ReceiptHandleIsInvalid' => ReceiptHandleIsInvalidException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
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
     * @throws BatchEntryIdsNotDistinctException
     * @throws EmptyBatchRequestException
     * @throws InvalidAddressException
     * @throws InvalidBatchEntryIdException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws TooManyEntriesInBatchRequestException
     * @throws UnsupportedOperationException
     */
    public function changeMessageVisibilityBatch($input): ChangeMessageVisibilityBatchResult
    {
        $input = ChangeMessageVisibilityBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangeMessageVisibilityBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'EmptyBatchRequest' => EmptyBatchRequestException::class,
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new ChangeMessageVisibilityBatchResult($response);
    }

    /**
     * Creates a new standard or FIFO queue. You can pass one or more attributes in the request. Keep the following in mind:
     *
     * - If you don't specify the `FifoQueue` attribute, Amazon SQS creates a standard queue.
     *
     *   > You can't change the queue type after you create it and you can't convert an existing standard queue into a FIFO
     *   > queue. You must either create a new FIFO queue for your application or delete your existing standard queue and
     *   > recreate it as a FIFO queue. For more information, see Moving From a standard queue to a FIFO queue [^1] in the
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
     * To retrieve the URL of a queue, use the `GetQueueUrl` [^3] action. This action only requires the `QueueName` [^4]
     * parameter.
     *
     * When creating queues, keep the following points in mind:
     *
     * - If you specify the name of an existing queue and provide the exact same names and values for all its attributes,
     *   the `CreateQueue` [^5] action will return the URL of the existing queue instead of creating a new one.
     * - If you attempt to create a queue with a name that already exists but with different attribute names or values, the
     *   `CreateQueue` action will return an error. This ensures that existing queues are not inadvertently altered.
     *
     * > Cross-account permissions don't apply to this action. For more information, see Grant cross-account permissions to
     * > a role and a username [^6] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html#FIFO-queues-moving
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/limits-queues.html
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html
     * [^4]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html#API_CreateQueue_RequestSyntax
     * [^5]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
     * [^6]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-customer-managed-policy-examples.html#grant-cross-account-permissions-to-role-and-user-name
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
     *
     * @param array{
     *   QueueName: string,
     *   Attributes?: null|array<QueueAttributeName::*, string>,
     *   tags?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|CreateQueueRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidAttributeNameException
     * @throws InvalidAttributeValueException
     * @throws InvalidSecurityException
     * @throws QueueDeletedRecentlyException
     * @throws QueueNameExistsException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function createQueue($input): CreateQueueResult
    {
        $input = CreateQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateQueue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidAttributeName' => InvalidAttributeNameException::class,
            'InvalidAttributeValue' => InvalidAttributeValueException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDeletedRecently' => QueueDeletedRecentlyException::class,
            'QueueNameExists' => QueueNameExistsException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new CreateQueueResult($response);
    }

    /**
     * Deletes the specified message from the specified queue. To select the message to delete, use the `ReceiptHandle` of
     * the message (*not* the `MessageId` which you receive when you send the message). Amazon SQS can delete a message from
     * a queue even if a visibility timeout setting causes the message to be locked by another consumer. Amazon SQS
     * automatically deletes messages left in a queue longer than the retention period configured for the queue.
     *
     * > Each time you receive a message, meaning when a consumer retrieves a message from the queue, it comes with a unique
     * > `ReceiptHandle`. If you receive the same message more than once, you will get a different `ReceiptHandle` each
     * > time. When you want to delete a message using the `DeleteMessage` action, you must use the `ReceiptHandle` from the
     * > most recent time you received the message. If you use an old `ReceiptHandle`, the request will succeed, but the
     * > message might not be deleted.
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
     * @throws InvalidAddressException
     * @throws InvalidIdFormatException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws ReceiptHandleIsInvalidException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function deleteMessage($input): Result
    {
        $input = DeleteMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidIdFormat' => InvalidIdFormatException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'ReceiptHandleIsInvalid' => ReceiptHandleIsInvalidException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
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
     * @throws BatchEntryIdsNotDistinctException
     * @throws EmptyBatchRequestException
     * @throws InvalidAddressException
     * @throws InvalidBatchEntryIdException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws TooManyEntriesInBatchRequestException
     * @throws UnsupportedOperationException
     */
    public function deleteMessageBatch($input): DeleteMessageBatchResult
    {
        $input = DeleteMessageBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteMessageBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'EmptyBatchRequest' => EmptyBatchRequestException::class,
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
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
     *
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function deleteQueue($input): Result
    {
        $input = DeleteQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteQueue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

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
     *   AttributeNames?: null|array<QueueAttributeName::*>,
     *   '@region'?: string|null,
     * }|GetQueueAttributesRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidAttributeNameException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function getQueueAttributes($input): GetQueueAttributesResult
    {
        $input = GetQueueAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueAttributes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidAttributeName' => InvalidAttributeNameException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new GetQueueAttributesResult($response);
    }

    /**
     * The `GetQueueUrl` API returns the URL of an existing Amazon SQS queue. This is useful when you know the queue's name
     * but need to retrieve its URL for further operations.
     *
     * To access a queue owned by another Amazon Web Services account, use the `QueueOwnerAWSAccountId` parameter to specify
     * the account ID of the queue's owner. Note that the queue owner must grant you the necessary permissions to access the
     * queue. For more information about accessing shared queues, see the `AddPermission` API or Allow developers to write
     * messages to a shared queue [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-writing-an-sqs-policy.html#write-messages-to-shared-queue
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: null|string,
     *   '@region'?: string|null,
     * }|GetQueueUrlRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function getQueueUrl($input): GetQueueUrlResult
    {
        $input = GetQueueUrlRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueUrl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
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
     *   QueueNamePrefix?: null|string,
     *   NextToken?: null|string,
     *   MaxResults?: null|int,
     *   '@region'?: string|null,
     * }|ListQueuesRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function listQueues($input = []): ListQueuesResult
    {
        $input = ListQueuesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListQueues', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new ListQueuesResult($response, $this, $input);
    }

    /**
     * Deletes available messages in a queue (including in-flight messages) specified by the `QueueURL` parameter.
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
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws PurgeQueueInProgressException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function purgeQueue($input): Result
    {
        $input = PurgeQueueRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PurgeQueue', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'PurgeQueueInProgress' => PurgeQueueInProgressException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new Result($response);
    }

    /**
     * @see getQueueUrl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: null|string,
     *   '@region'?: string|null,
     * }|GetQueueUrlRequest $input
     */
    public function queueExists($input): QueueExistsWaiter
    {
        $input = GetQueueUrlRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueUrl', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new QueueExistsWaiter($response, $this, $input);
    }

    /**
     * Retrieves one or more messages (up to 10), from the specified queue. Using the `WaitTimeSeconds` parameter enables
     * long-poll support. For more information, see Amazon SQS Long Polling [^1] in the *Amazon SQS Developer Guide*.
     *
     * Short poll is the default behavior where a weighted random set of machines is sampled on a `ReceiveMessage` call.
     * Therefore, only the messages on the sampled machines are returned. If the number of messages in the queue is small
     * (fewer than 1,000), you most likely get fewer messages than you requested per `ReceiveMessage` call. If the number of
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
     * is used for the returned messages. The default visibility timeout for a queue is 30 seconds.
     *
     * > In the future, new attributes might be added. If you write code that calls this action, we recommend that you
     * > structure your code so that it can handle new attributes gracefully.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-long-polling.html
     * [^2]: https://www.ietf.org/rfc/rfc1321.txt
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-message-identifiers.html
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ReceiveMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageSystemAttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: null|string[],
     *   MaxNumberOfMessages?: null|int,
     *   VisibilityTimeout?: null|int,
     *   WaitTimeSeconds?: null|int,
     *   ReceiveRequestAttemptId?: null|string,
     *   '@region'?: string|null,
     * }|ReceiveMessageRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidSecurityException
     * @throws KmsAccessDeniedException
     * @throws KmsDisabledException
     * @throws KmsInvalidKeyUsageException
     * @throws KmsInvalidStateException
     * @throws KmsNotFoundException
     * @throws KmsOptInRequiredException
     * @throws KmsThrottledException
     * @throws OverLimitException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function receiveMessage($input): ReceiveMessageResult
    {
        $input = ReceiveMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ReceiveMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'KmsAccessDenied' => KmsAccessDeniedException::class,
            'KmsDisabled' => KmsDisabledException::class,
            'KmsInvalidKeyUsage' => KmsInvalidKeyUsageException::class,
            'KmsInvalidState' => KmsInvalidStateException::class,
            'KmsNotFound' => KmsNotFoundException::class,
            'KmsOptInRequired' => KmsOptInRequiredException::class,
            'KmsThrottled' => KmsThrottledException::class,
            'OverLimit' => OverLimitException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new ReceiveMessageResult($response);
    }

    /**
     * Delivers a message to the specified queue.
     *
     * ! A message can include only XML, JSON, and unformatted text. The following Unicode characters are allowed. For more
     * ! information, see the W3C specification for characters [^1].
     * !
     * ! `#x9` | `#xA` | `#xD` | `#x20` to `#xD7FF` | `#xE000` to `#xFFFD` | `#x10000` to `#x10FFFF`
     * !
     * ! Amazon SQS does not throw an exception or completely reject the message if it contains invalid characters. Instead,
     * ! it replaces those invalid characters with U+FFFD before storing the message in the queue, as long as the message
     * ! body contains at least one valid character.
     *
     * [^1]: http://www.w3.org/TR/REC-xml/#charsets
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     *
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     *   '@region'?: string|null,
     * }|SendMessageRequest $input
     *
     * @throws InvalidAddressException
     * @throws InvalidMessageContentsException
     * @throws InvalidSecurityException
     * @throws KmsAccessDeniedException
     * @throws KmsDisabledException
     * @throws KmsInvalidKeyUsageException
     * @throws KmsInvalidStateException
     * @throws KmsNotFoundException
     * @throws KmsOptInRequiredException
     * @throws KmsThrottledException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws UnsupportedOperationException
     */
    public function sendMessage($input): SendMessageResult
    {
        $input = SendMessageRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendMessage', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidMessageContents' => InvalidMessageContentsException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'KmsAccessDenied' => KmsAccessDeniedException::class,
            'KmsDisabled' => KmsDisabledException::class,
            'KmsInvalidKeyUsage' => KmsInvalidKeyUsageException::class,
            'KmsInvalidState' => KmsInvalidStateException::class,
            'KmsNotFound' => KmsNotFoundException::class,
            'KmsOptInRequired' => KmsOptInRequiredException::class,
            'KmsThrottled' => KmsThrottledException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
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
     * all of the batched messages) are both 1 MiB 1,048,576 bytes.
     *
     * ! A message can include only XML, JSON, and unformatted text. The following Unicode characters are allowed. For more
     * ! information, see the W3C specification for characters [^1].
     * !
     * ! `#x9` | `#xA` | `#xD` | `#x20` to `#xD7FF` | `#xE000` to `#xFFFD` | `#x10000` to `#x10FFFF`
     * !
     * ! Amazon SQS does not throw an exception or completely reject the message if it contains invalid characters. Instead,
     * ! it replaces those invalid characters with U+FFFD before storing the message in the queue, as long as the message
     * ! body contains at least one valid character.
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
     * @throws BatchEntryIdsNotDistinctException
     * @throws BatchRequestTooLongException
     * @throws EmptyBatchRequestException
     * @throws InvalidAddressException
     * @throws InvalidBatchEntryIdException
     * @throws InvalidSecurityException
     * @throws KmsAccessDeniedException
     * @throws KmsDisabledException
     * @throws KmsInvalidKeyUsageException
     * @throws KmsInvalidStateException
     * @throws KmsNotFoundException
     * @throws KmsOptInRequiredException
     * @throws KmsThrottledException
     * @throws QueueDoesNotExistException
     * @throws RequestThrottledException
     * @throws TooManyEntriesInBatchRequestException
     * @throws UnsupportedOperationException
     */
    public function sendMessageBatch($input): SendMessageBatchResult
    {
        $input = SendMessageBatchRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SendMessageBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'BatchRequestTooLong' => BatchRequestTooLongException::class,
            'EmptyBatchRequest' => EmptyBatchRequestException::class,
            'InvalidAddress' => InvalidAddressException::class,
            'InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'KmsAccessDenied' => KmsAccessDeniedException::class,
            'KmsDisabled' => KmsDisabledException::class,
            'KmsInvalidKeyUsage' => KmsInvalidKeyUsageException::class,
            'KmsInvalidState' => KmsInvalidStateException::class,
            'KmsNotFound' => KmsNotFoundException::class,
            'KmsOptInRequired' => KmsOptInRequiredException::class,
            'KmsThrottled' => KmsThrottledException::class,
            'QueueDoesNotExist' => QueueDoesNotExistException::class,
            'RequestThrottled' => RequestThrottledException::class,
            'TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'UnsupportedOperation' => UnsupportedOperationException::class,
        ]]));

        return new SendMessageBatchResult($response);
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
                    'endpoint' => "https://sqs.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://sqs-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://sqs-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://sqs.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://sqs.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://sqs.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-iso-east-1':
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://sqs.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-iso-west-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => 'https://sqs.us-iso-west-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-isob-east-1':
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://sqs.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-isof-east-1':
            case 'us-isof-east-1':
                return [
                    'endpoint' => 'https://sqs.us-isof-east-1.csp.hci.ic.gov',
                    'signRegion' => 'us-isof-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-isof-south-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => 'https://sqs.us-isof-south-1.csp.hci.ic.gov',
                    'signRegion' => 'us-isof-south-1',
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
