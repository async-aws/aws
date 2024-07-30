<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\QueueAttributeName;

final class GetQueueAttributesRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue whose attribute information is retrieved.
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * A list of attributes for which to retrieve information.
     *
     * The `AttributeNames` parameter is optional, but if you don't specify values for this parameter, the request returns
     * empty results.
     *
     * > In the future, new attributes might be added. If you write code that calls this action, we recommend that you
     * > structure your code so that it can handle new attributes gracefully.
     *
     * The following attributes are supported:
     *
     * ! The `ApproximateNumberOfMessagesDelayed`, `ApproximateNumberOfMessagesNotVisible`, and
     * ! `ApproximateNumberOfMessages` metrics may not achieve consistency until at least 1 minute after the producers stop
     * ! sending messages. This period is required for the queue metadata to reach eventual consistency.
     *
     * - `All` – Returns all values.
     * - `ApproximateNumberOfMessages` – Returns the approximate number of messages available for retrieval from the
     *   queue.
     * - `ApproximateNumberOfMessagesDelayed` – Returns the approximate number of messages in the queue that are delayed
     *   and not available for reading immediately. This can happen when the queue is configured as a delay queue or when a
     *   message has been sent with a delay parameter.
     * - `ApproximateNumberOfMessagesNotVisible` – Returns the approximate number of messages that are in flight. Messages
     *   are considered to be *in flight* if they have been sent to a client but have not yet been deleted or have not yet
     *   reached the end of their visibility window.
     * - `CreatedTimestamp` – Returns the time when the queue was created in seconds (epoch time [^1]).
     * - `DelaySeconds` – Returns the default delay on the queue in seconds.
     * - `LastModifiedTimestamp` – Returns the time when the queue was last changed in seconds (epoch time [^2]).
     * - `MaximumMessageSize` – Returns the limit of how many bytes a message can contain before Amazon SQS rejects it.
     * - `MessageRetentionPeriod` – Returns the length of time, in seconds, for which Amazon SQS retains a message. When
     *   you change a queue's attributes, the change can take up to 60 seconds for most of the attributes to propagate
     *   throughout the Amazon SQS system. Changes made to the `MessageRetentionPeriod` attribute can take up to 15 minutes
     *   and will impact existing messages in the queue potentially causing them to be expired and deleted if the
     *   `MessageRetentionPeriod` is reduced below the age of existing messages.
     * - `Policy` – Returns the policy of the queue.
     * - `QueueArn` – Returns the Amazon resource name (ARN) of the queue.
     * - `ReceiveMessageWaitTimeSeconds` – Returns the length of time, in seconds, for which the `ReceiveMessage` action
     *   waits for a message to arrive.
     * - `VisibilityTimeout` – Returns the visibility timeout for the queue. For more information about the visibility
     *   timeout, see Visibility Timeout [^3] in the *Amazon SQS Developer Guide*.
     *
     * The following attributes apply only to dead-letter queues: [^4]
     *
     * - `RedrivePolicy` – The string that includes the parameters for the dead-letter queue functionality of the source
     *   queue as a JSON object. The parameters are as follows:
     *
     *   - `deadLetterTargetArn` – The Amazon Resource Name (ARN) of the dead-letter queue to which Amazon SQS moves
     *     messages after the value of `maxReceiveCount` is exceeded.
     *   - `maxReceiveCount` – The number of times a message is delivered to the source queue before being moved to the
     *     dead-letter queue. Default: 10. When the `ReceiveCount` for a message exceeds the `maxReceiveCount` for a queue,
     *     Amazon SQS moves the message to the dead-letter-queue.
     *
     * - `RedriveAllowPolicy` – The string that includes the parameters for the permissions for the dead-letter queue
     *   redrive permission and which source queues can specify dead-letter queues as a JSON object. The parameters are as
     *   follows:
     *
     *   - `redrivePermission` – The permission type that defines which source queues can specify the current queue as the
     *     dead-letter queue. Valid values are:
     *
     *     - `allowAll` – (Default) Any source queues in this Amazon Web Services account in the same Region can specify
     *       this queue as the dead-letter queue.
     *     - `denyAll` – No source queues can specify this queue as the dead-letter queue.
     *     - `byQueue` – Only queues specified by the `sourceQueueArns` parameter can specify this queue as the
     *       dead-letter queue.
     *
     *   - `sourceQueueArns` – The Amazon Resource Names (ARN)s of the source queues that can specify this queue as the
     *     dead-letter queue and redrive messages. You can specify this parameter only when the `redrivePermission`
     *     parameter is set to `byQueue`. You can specify up to 10 source queue ARNs. To allow more than 10 source queues to
     *     specify dead-letter queues, set the `redrivePermission` parameter to `allowAll`.
     *
     *
     * > The dead-letter queue of a FIFO queue must also be a FIFO queue. Similarly, the dead-letter queue of a standard
     * > queue must also be a standard queue.
     *
     * The following attributes apply only to server-side-encryption [^5]:
     *
     * - `KmsMasterKeyId` – Returns the ID of an Amazon Web Services managed customer master key (CMK) for Amazon SQS or a
     *   custom CMK. For more information, see Key Terms [^6].
     * - `KmsDataKeyReusePeriodSeconds` – Returns the length of time, in seconds, for which Amazon SQS can reuse a data
     *   key to encrypt or decrypt messages before calling KMS again. For more information, see How Does the Data Key Reuse
     *   Period Work? [^7].
     * - `SqsManagedSseEnabled` – Returns information about whether the queue is using SSE-SQS encryption using SQS owned
     *   encryption keys. Only one server-side encryption option is supported per queue (for example, SSE-KMS [^8] or
     *   SSE-SQS [^9]).
     *
     * The following attributes apply only to FIFO (first-in-first-out) queues [^10]:
     *
     * - `FifoQueue` – Returns information about whether the queue is FIFO. For more information, see FIFO queue logic
     *   [^11] in the *Amazon SQS Developer Guide*.
     *
     *   > To determine whether a queue is FIFO [^12], you can check whether `QueueName` ends with the `.fifo` suffix.
     *
     * - `ContentBasedDeduplication` – Returns whether content-based deduplication is enabled for the queue. For more
     *   information, see Exactly-once processing [^13] in the *Amazon SQS Developer Guide*.
     *
     * The following attributes apply only to high throughput for FIFO queues [^14]:
     *
     * - `DeduplicationScope` – Specifies whether message deduplication occurs at the message group or queue level. Valid
     *   values are `messageGroup` and `queue`.
     * - `FifoThroughputLimit` – Specifies whether the FIFO queue throughput quota applies to the entire queue or per
     *   message group. Valid values are `perQueue` and `perMessageGroupId`. The `perMessageGroupId` value is allowed only
     *   when the value for `DeduplicationScope` is `messageGroup`.
     *
     * To enable high throughput for FIFO queues, do the following:
     *
     * - Set `DeduplicationScope` to `messageGroup`.
     * - Set `FifoThroughputLimit` to `perMessageGroupId`.
     *
     * If you set these attributes to anything other than the values shown for enabling high throughput, normal throughput
     * is in effect and deduplication occurs as specified.
     *
     * For information on throughput quotas, see Quotas related to messages [^15] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: http://en.wikipedia.org/wiki/Unix_time
     * [^2]: http://en.wikipedia.org/wiki/Unix_time
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     * [^4]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-dead-letter-queues.html
     * [^5]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-server-side-encryption.html
     * [^6]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-server-side-encryption.html#sqs-sse-key-terms
     * [^7]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-server-side-encryption.html#sqs-how-does-the-data-key-reuse-period-work
     * [^8]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-configure-sse-existing-queue.html
     * [^9]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-configure-sqs-sse-queue.html
     * [^10]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html
     * [^11]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues-understanding-logic.html
     * [^12]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html
     * [^13]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues-exactly-once-processing.html
     * [^14]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/high-throughput-fifo.html
     * [^15]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/quotas-messages.html
     *
     * @var list<QueueAttributeName::*>|null
     */
    private $attributeNames;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: null|array<QueueAttributeName::*>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->attributeNames = $input['AttributeNames'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: null|array<QueueAttributeName::*>,
     *   '@region'?: string|null,
     * }|GetQueueAttributesRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<QueueAttributeName::*>
     */
    public function getAttributeNames(): array
    {
        return $this->attributeNames ?? [];
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.GetQueueAttributes',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<QueueAttributeName::*> $value
     */
    public function setAttributeNames(array $value): self
    {
        $this->attributeNames = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null !== $v = $this->attributeNames) {
            $index = -1;
            $payload['AttributeNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!QueueAttributeName::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "AttributeNames" for "%s". The value "%s" is not a valid "QueueAttributeName".', __CLASS__, $listValue));
                }
                $payload['AttributeNames'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
