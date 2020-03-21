<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use AsyncAws\Sqs\Input\CreateQueueRequest;
use AsyncAws\Sqs\Input\DeleteMessageRequest;
use AsyncAws\Sqs\Input\DeleteQueueRequest;
use AsyncAws\Sqs\Input\GetQueueAttributesRequest;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\ListQueuesRequest;
use AsyncAws\Sqs\Input\PurgeQueueRequest;
use AsyncAws\Sqs\Input\ReceiveMessageRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\Result\CreateQueueResult;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use AsyncAws\Sqs\Result\ListQueuesResult;
use AsyncAws\Sqs\Result\QueueExists;
use AsyncAws\Sqs\Result\QueueExistsWaiter;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use AsyncAws\Sqs\Result\SendMessageResult;

class SqsClient extends AbstractApi
{
    /**
     * Changes the visibility timeout of a specified message in a queue to a new value. The default visibility timeout for a
     * message is 30 seconds. The minimum is 0 seconds. The maximum is 12 hours. For more information, see Visibility
     * Timeout in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#changemessagevisibility
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout: int,
     * }|ChangeMessageVisibilityRequest $input
     */
    public function changeMessageVisibility($input): Result
    {
        $response = $this->getResponse(ChangeMessageVisibilityRequest::create($input)->request(), new RequestContext(['operation' => 'ChangeMessageVisibility']));

        return new Result($response);
    }

    /**
     * Creates a new standard or FIFO queue. You can pass one or more attributes in the request. Keep the following caveats
     * in mind:.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
     *
     * @param array{
     *   QueueName: string,
     *   Attributes?: string[],
     *   tags?: string[],
     * }|CreateQueueRequest $input
     */
    public function createQueue($input): CreateQueueResult
    {
        $response = $this->getResponse(CreateQueueRequest::create($input)->request(), new RequestContext(['operation' => 'CreateQueue']));

        return new CreateQueueResult($response);
    }

    /**
     * Deletes the specified message from the specified queue. To select the message to delete, use the `ReceiptHandle` of
     * the message (*not* the `MessageId` which you receive when you send the message). Amazon SQS can delete a message from
     * a queue even if a visibility timeout setting causes the message to be locked by another consumer. Amazon SQS
     * automatically deletes messages left in a queue longer than the retention period configured for the queue.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     * }|DeleteMessageRequest $input
     */
    public function deleteMessage($input): Result
    {
        $response = $this->getResponse(DeleteMessageRequest::create($input)->request(), new RequestContext(['operation' => 'DeleteMessage']));

        return new Result($response);
    }

    /**
     * Deletes the queue specified by the `QueueUrl`, regardless of the queue's contents. If the specified queue doesn't
     * exist, Amazon SQS returns a successful response.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#deletequeue
     *
     * @param array{
     *   QueueUrl: string,
     * }|DeleteQueueRequest $input
     */
    public function deleteQueue($input): Result
    {
        $response = $this->getResponse(DeleteQueueRequest::create($input)->request(), new RequestContext(['operation' => 'DeleteQueue']));

        return new Result($response);
    }

    /**
     * Gets attributes for the specified queue.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueattributes
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: list<\AsyncAws\Sqs\Enum\QueueAttributeName::*>,
     * }|GetQueueAttributesRequest $input
     */
    public function getQueueAttributes($input): GetQueueAttributesResult
    {
        $response = $this->getResponse(GetQueueAttributesRequest::create($input)->request(), new RequestContext(['operation' => 'GetQueueAttributes']));

        return new GetQueueAttributesResult($response);
    }

    /**
     * Returns the URL of an existing Amazon SQS queue.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueurl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: string,
     * }|GetQueueUrlRequest $input
     */
    public function getQueueUrl($input): GetQueueUrlResult
    {
        $response = $this->getResponse(GetQueueUrlRequest::create($input)->request(), new RequestContext(['operation' => 'GetQueueUrl']));

        return new GetQueueUrlResult($response);
    }

    /**
     * Returns a list of your queues. The maximum number of queues that can be returned is 1,000. If you specify a value for
     * the optional `QueueNamePrefix` parameter, only queues with a name that begins with the specified value are returned.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#listqueues
     *
     * @param array{
     *   QueueNamePrefix?: string,
     * }|ListQueuesRequest $input
     */
    public function listQueues($input = []): ListQueuesResult
    {
        $response = $this->getResponse(ListQueuesRequest::create($input)->request(), new RequestContext(['operation' => 'ListQueues']));

        return new ListQueuesResult($response);
    }

    /**
     * Deletes the messages in a queue specified by the `QueueURL` parameter.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#purgequeue
     *
     * @param array{
     *   QueueUrl: string,
     * }|PurgeQueueRequest $input
     */
    public function purgeQueue($input): Result
    {
        $response = $this->getResponse(PurgeQueueRequest::create($input)->request(), new RequestContext(['operation' => 'PurgeQueue']));

        return new Result($response);
    }

    /**
     * Check status of operation getQueueUrl.
     *
     * @see getQueueUrl
     *
     * @param array{
     *   QueueName: string,
     *   QueueOwnerAWSAccountId?: string,
     * }|GetQueueUrlRequest $input
     */
    public function queueExists($input): QueueExistsWaiter
    {
        $input = GetQueueUrlRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueueUrl']));

        return new QueueExistsWaiter($response, $this, $input);
    }

    /**
     * Retrieves one or more messages (up to 10), from the specified queue. Using the `WaitTimeSeconds` parameter enables
     * long-poll support. For more information, see Amazon SQS Long Polling in the *Amazon Simple Queue Service Developer
     * Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-long-polling.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#receivemessage
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: list<\AsyncAws\Sqs\Enum\QueueAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     * }|ReceiveMessageRequest $input
     */
    public function receiveMessage($input): ReceiveMessageResult
    {
        $response = $this->getResponse(ReceiveMessageRequest::create($input)->request(), new RequestContext(['operation' => 'ReceiveMessage']));

        return new ReceiveMessageResult($response);
    }

    /**
     * Delivers a message to the specified queue.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
     *
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: \AsyncAws\Sqs\ValueObject\MessageAttributeValue[],
     *   MessageSystemAttributes?: \AsyncAws\Sqs\ValueObject\MessageSystemAttributeValue[],
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     * }|SendMessageRequest $input
     */
    public function sendMessage($input): SendMessageResult
    {
        $response = $this->getResponse(SendMessageRequest::create($input)->request(), new RequestContext(['operation' => 'SendMessage']));

        return new SendMessageResult($response);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }

    protected function getSignatureScopeName(): string
    {
        return 'sqs';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
