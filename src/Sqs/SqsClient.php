<?php

namespace AsyncAws\Sqs;

use AsyncAws\Core\AbstractApi;
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
        $input = ChangeMessageVisibilityRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new Result($response, $this->httpClient);
    }

    /**
     * Creates a new standard or FIFO queue. You can pass one or more attributes in the request. Keep the following caveats
     * in mind:.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#createqueue
     *
     * @param array{
     *   QueueName: string,
     *   Attributes?: array,
     *   tags?: array,
     * }|CreateQueueRequest $input
     */
    public function createQueue($input): CreateQueueResult
    {
        $input = CreateQueueRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new CreateQueueResult($response, $this->httpClient);
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
        $input = DeleteMessageRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new Result($response, $this->httpClient);
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
        $input = DeleteQueueRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new Result($response, $this->httpClient);
    }

    /**
     * Gets attributes for the specified queue.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#getqueueattributes
     *
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: string[],
     * }|GetQueueAttributesRequest $input
     */
    public function getQueueAttributes($input): GetQueueAttributesResult
    {
        $input = GetQueueAttributesRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new GetQueueAttributesResult($response, $this->httpClient);
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
        $input = GetQueueUrlRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new GetQueueUrlResult($response, $this->httpClient);
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
        $input = ListQueuesRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new ListQueuesResult($response, $this->httpClient);
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
        $input = PurgeQueueRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new Result($response, $this->httpClient);
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
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new QueueExistsWaiter($response, $this->httpClient, $this, $input);
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
     *   AttributeNames?: string[],
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     * }|ReceiveMessageRequest $input
     */
    public function receiveMessage($input): ReceiveMessageResult
    {
        $input = ReceiveMessageRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new ReceiveMessageResult($response, $this->httpClient);
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
     *   MessageAttributes?: array,
     *   MessageSystemAttributes?: array,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     * }|SendMessageRequest $input
     */
    public function sendMessage($input): SendMessageResult
    {
        $input = SendMessageRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new SendMessageResult($response, $this->httpClient);
    }

    protected function getServiceCode(): string
    {
        return 'sqs';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
