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
     * Timeout in the *Amazon SQS Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibility.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#changemessagevisibility
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout: int,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibilityBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#changemessagevisibilitybatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: ChangeMessageVisibilityBatchRequestEntry[],
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
     *
     * @param array{
     *   QueueName: string,
     *   Attributes?: array<QueueAttributeName::*, string>,
     *   tags?: array<string, string>,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessageBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessagebatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: DeleteMessageBatchRequestEntry[],
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletequeue
     *
     * @param array{
     *   QueueUrl: string,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueattributes
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: list<QueueAttributeName::*>,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: string,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListQueues.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#listqueues
     *
     * @param array{
     *   QueueNamePrefix?: string,
     *   NextToken?: string,
     *   MaxResults?: int,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_PurgeQueue.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#purgequeue
     *
     * @param array{
     *   QueueUrl: string,
     *   @region?: string,
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
     *   @region?: string,
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
     * long-poll support. For more information, see Amazon SQS Long Polling in the *Amazon SQS Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-long-polling.html
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ReceiveMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: list<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     *   @region?: string,
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
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessage.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     *
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: array<string, MessageAttributeValue>,
     *   MessageSystemAttributes?: array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   @region?: string,
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
     * Delivers up to ten messages to the specified queue. This is a batch version of `SendMessage.` For a FIFO queue,
     * multiple messages within a single batch are enqueued in the order they are sent.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessagebatch
     *
     * @param array{
     *   QueueUrl: string,
     *   Entries: SendMessageBatchRequestEntry[],
     *   @region?: string,
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
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://sqs.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://sqs.$region.sc2s.sgov.gov",
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
            case 'us-east-1':
                return [
                    'endpoint' => 'https://sqs.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2':
                return [
                    'endpoint' => 'https://sqs.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://sqs.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://sqs.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://sqs.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1':
                return [
                    'endpoint' => 'https://sqs.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'sqs',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2':
                return [
                    'endpoint' => 'https://sqs.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
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
