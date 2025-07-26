<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;

/**
 * Retrieves one or more messages from a specified queue.
 */
final class ReceiveMessageRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue from which messages are received.
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * @var list<MessageSystemAttributeName::*>|null
     */
    private $attributeNames;

    /**
     * A list of attributes that need to be returned along with each message. These attributes include:
     *
     * - `All` – Returns all values.
     * - `ApproximateFirstReceiveTimestamp` – Returns the time the message was first received from the queue (epoch time
     *   [^1] in milliseconds).
     * - `ApproximateReceiveCount` – Returns the number of times a message has been received across all queues but not
     *   deleted.
     * - `AWSTraceHeader` – Returns the X-Ray trace header string.
     * - `SenderId`
     *
     *   - For a user, returns the user ID, for example `ABCDEFGHI1JKLMNOPQ23R`.
     *   - For an IAM role, returns the IAM role ID, for example `ABCDE1F2GH3I4JK5LMNOP:i-a123b456`.
     *
     * - `SentTimestamp` – Returns the time the message was sent to the queue (epoch time [^2] in milliseconds).
     * - `SqsManagedSseEnabled` – Enables server-side queue encryption using SQS owned encryption keys. Only one
     *   server-side encryption option is supported per queue (for example, SSE-KMS [^3] or SSE-SQS [^4]).
     * - `MessageDeduplicationId` – Returns the value provided by the producer that calls the `SendMessage` action.
     * - `MessageGroupId` – Returns the value provided by the producer that calls the `SendMessage` action.
     * - `SequenceNumber` – Returns the value provided by Amazon SQS.
     *
     * [^1]: http://en.wikipedia.org/wiki/Unix_time
     * [^2]: http://en.wikipedia.org/wiki/Unix_time
     * [^3]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-configure-sse-existing-queue.html
     * [^4]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-configure-sqs-sse-queue.html
     *
     * @var list<MessageSystemAttributeName::*>|null
     */
    private $messageSystemAttributeNames;

    /**
     * The name of the message attribute, where *N* is the index.
     *
     * - The name can contain alphanumeric characters and the underscore (`_`), hyphen (`-`), and period (`.`).
     * - The name is case-sensitive and must be unique among all attribute names for the message.
     * - The name must not start with AWS-reserved prefixes such as `AWS.` or `Amazon.` (or any casing variants).
     * - The name must not start or end with a period (`.`), and it should not have periods in succession (`..`).
     * - The name can be up to 256 characters long.
     *
     * When using `ReceiveMessage`, you can send a list of attribute names to receive, or you can return all of the
     * attributes by specifying `All` or `.*` in your request. You can also use all message attributes starting with a
     * prefix, for example `bar.*`.
     *
     * @var string[]|null
     */
    private $messageAttributeNames;

    /**
     * The maximum number of messages to return. Amazon SQS never returns more messages than this value (however, fewer
     * messages might be returned). Valid values: 1 to 10. Default: 1.
     *
     * @var int|null
     */
    private $maxNumberOfMessages;

    /**
     * The duration (in seconds) that the received messages are hidden from subsequent retrieve requests after being
     * retrieved by a `ReceiveMessage` request. If not specified, the default visibility timeout for the queue is used,
     * which is 30 seconds.
     *
     * Understanding `VisibilityTimeout`:
     *
     * - When a message is received from a queue, it becomes temporarily invisible to other consumers for the duration of
     *   the visibility timeout. This prevents multiple consumers from processing the same message simultaneously. If the
     *   message is not deleted or its visibility timeout is not extended before the timeout expires, it becomes visible
     *   again and can be retrieved by other consumers.
     * - Setting an appropriate visibility timeout is crucial. If it's too short, the message might become visible again
     *   before processing is complete, leading to duplicate processing. If it's too long, it delays the reprocessing of
     *   messages if the initial processing fails.
     * - You can adjust the visibility timeout using the `--visibility-timeout` parameter in the `receive-message` command
     *   to match the processing time required by your application.
     * - A message that isn't deleted or a message whose visibility isn't extended before the visibility timeout expires
     *   counts as a failed receive. Depending on the configuration of the queue, the message might be sent to the
     *   dead-letter queue.
     *
     * For more information, see Visibility Timeout [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     *
     * @var int|null
     */
    private $visibilityTimeout;

    /**
     * The duration (in seconds) for which the call waits for a message to arrive in the queue before returning. If a
     * message is available, the call returns sooner than `WaitTimeSeconds`. If no messages are available and the wait time
     * expires, the call does not return a message list. If you are using the Java SDK, it returns a
     * `ReceiveMessageResponse` object, which has a empty list instead of a Null object.
     *
     * ! To avoid HTTP errors, ensure that the HTTP response timeout for `ReceiveMessage` requests is longer than the
     * ! `WaitTimeSeconds` parameter. For example, with the Java SDK, you can set HTTP transport settings using the
     * ! NettyNioAsyncHttpClient [^1] for asynchronous clients, or the ApacheHttpClient [^2] for synchronous clients.
     *
     * [^1]: https://sdk.amazonaws.com/java/api/latest/software/amazon/awssdk/http/nio/netty/NettyNioAsyncHttpClient.html
     * [^2]: https://sdk.amazonaws.com/java/api/latest/software/amazon/awssdk/http/apache/ApacheHttpClient.html
     *
     * @var int|null
     */
    private $waitTimeSeconds;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * The token used for deduplication of `ReceiveMessage` calls. If a networking issue occurs after a `ReceiveMessage`
     * action, and instead of a response you receive a generic error, it is possible to retry the same action with an
     * identical `ReceiveRequestAttemptId` to retrieve the same set of messages, even if their visibility timeout has not
     * yet expired.
     *
     * - You can use `ReceiveRequestAttemptId` only for 5 minutes after a `ReceiveMessage` action.
     * - When you set `FifoQueue`, a caller of the `ReceiveMessage` action can provide a `ReceiveRequestAttemptId`
     *   explicitly.
     * - It is possible to retry the `ReceiveMessage` action with the same `ReceiveRequestAttemptId` if none of the messages
     *   have been modified (deleted or had their visibility changes).
     * - During a visibility timeout, subsequent calls with the same `ReceiveRequestAttemptId` return the same messages and
     *   receipt handles. If a retry occurs within the deduplication interval, it resets the visibility timeout. For more
     *   information, see Visibility Timeout [^1] in the *Amazon SQS Developer Guide*.
     *
     *   ! If a caller of the `ReceiveMessage` action still processes messages when the visibility timeout expires and
     *   ! messages become visible, another worker consuming from the same queue can receive the same messages and therefore
     *   ! process duplicates. Also, if a consumer whose message processing time is longer than the visibility timeout tries
     *   ! to delete the processed messages, the action fails with an error.
     *   !
     *   ! To mitigate this effect, ensure that your application observes a safe threshold before the visibility timeout
     *   ! expires and extend the visibility timeout as necessary.
     *
     * - While messages with a particular `MessageGroupId` are invisible, no more messages belonging to the same
     *   `MessageGroupId` are returned until the visibility timeout expires. You can still receive messages with another
     *   `MessageGroupId` from your FIFO queue as long as they are visible.
     * - If a caller of `ReceiveMessage` can't track the `ReceiveRequestAttemptId`, no retries work until the original
     *   visibility timeout expires. As a result, delays might occur but the messages in the queue remain in a strict order.
     *
     * The maximum length of `ReceiveRequestAttemptId` is 128 characters. `ReceiveRequestAttemptId` can contain alphanumeric
     * characters (`a-z`, `A-Z`, `0-9`) and punctuation (`!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~`).
     *
     * For best practices of using `ReceiveRequestAttemptId`, see Using the ReceiveRequestAttemptId Request Parameter [^2]
     * in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-visibility-timeout.html
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-receiverequestattemptid-request-parameter.html
     *
     * @var string|null
     */
    private $receiveRequestAttemptId;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageSystemAttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: null|string[],
     *   MaxNumberOfMessages?: null|int,
     *   VisibilityTimeout?: null|int,
     *   WaitTimeSeconds?: null|int,
     *   ReceiveRequestAttemptId?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->attributeNames = $input['AttributeNames'] ?? null;
        $this->messageSystemAttributeNames = $input['MessageSystemAttributeNames'] ?? null;
        $this->messageAttributeNames = $input['MessageAttributeNames'] ?? null;
        $this->maxNumberOfMessages = $input['MaxNumberOfMessages'] ?? null;
        $this->visibilityTimeout = $input['VisibilityTimeout'] ?? null;
        $this->waitTimeSeconds = $input['WaitTimeSeconds'] ?? null;
        $this->receiveRequestAttemptId = $input['ReceiveRequestAttemptId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageSystemAttributeNames?: null|array<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: null|string[],
     *   MaxNumberOfMessages?: null|int,
     *   VisibilityTimeout?: null|int,
     *   WaitTimeSeconds?: null|int,
     *   ReceiveRequestAttemptId?: null|string,
     *   '@region'?: string|null,
     * }|ReceiveMessageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @deprecated
     *
     * @return list<MessageSystemAttributeName::*>
     */
    public function getAttributeNames(): array
    {
        @trigger_error(\sprintf('The property "AttributeNames" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->attributeNames ?? [];
    }

    public function getMaxNumberOfMessages(): ?int
    {
        return $this->maxNumberOfMessages;
    }

    /**
     * @return string[]
     */
    public function getMessageAttributeNames(): array
    {
        return $this->messageAttributeNames ?? [];
    }

    /**
     * @return list<MessageSystemAttributeName::*>
     */
    public function getMessageSystemAttributeNames(): array
    {
        return $this->messageSystemAttributeNames ?? [];
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    public function getReceiveRequestAttemptId(): ?string
    {
        return $this->receiveRequestAttemptId;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->visibilityTimeout;
    }

    public function getWaitTimeSeconds(): ?int
    {
        return $this->waitTimeSeconds;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.ReceiveMessage',
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
     * @deprecated
     *
     * @param list<MessageSystemAttributeName::*> $value
     */
    public function setAttributeNames(array $value): self
    {
        @trigger_error(\sprintf('The property "AttributeNames" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->attributeNames = $value;

        return $this;
    }

    public function setMaxNumberOfMessages(?int $value): self
    {
        $this->maxNumberOfMessages = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setMessageAttributeNames(array $value): self
    {
        $this->messageAttributeNames = $value;

        return $this;
    }

    /**
     * @param list<MessageSystemAttributeName::*> $value
     */
    public function setMessageSystemAttributeNames(array $value): self
    {
        $this->messageSystemAttributeNames = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    public function setReceiveRequestAttemptId(?string $value): self
    {
        $this->receiveRequestAttemptId = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->visibilityTimeout = $value;

        return $this;
    }

    public function setWaitTimeSeconds(?int $value): self
    {
        $this->waitTimeSeconds = $value;

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
            @trigger_error(\sprintf('The property "AttributeNames" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            $index = -1;
            $payload['AttributeNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!MessageSystemAttributeName::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "AttributeNames" for "%s". The value "%s" is not a valid "MessageSystemAttributeName".', __CLASS__, $listValue));
                }
                $payload['AttributeNames'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->messageSystemAttributeNames) {
            $index = -1;
            $payload['MessageSystemAttributeNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!MessageSystemAttributeName::exists($listValue)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "MessageSystemAttributeNames" for "%s". The value "%s" is not a valid "MessageSystemAttributeName".', __CLASS__, $listValue));
                }
                $payload['MessageSystemAttributeNames'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->messageAttributeNames) {
            $index = -1;
            $payload['MessageAttributeNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['MessageAttributeNames'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->maxNumberOfMessages) {
            $payload['MaxNumberOfMessages'] = $v;
        }
        if (null !== $v = $this->visibilityTimeout) {
            $payload['VisibilityTimeout'] = $v;
        }
        if (null !== $v = $this->waitTimeSeconds) {
            $payload['WaitTimeSeconds'] = $v;
        }
        if (null !== $v = $this->receiveRequestAttemptId) {
            $payload['ReceiveRequestAttemptId'] = $v;
        }

        return $payload;
    }
}
