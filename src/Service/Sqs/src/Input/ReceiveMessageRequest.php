<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\MessageSystemAttributeName;

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
     * retrieved by a `ReceiveMessage` request.
     *
     * @var int|null
     */
    private $visibilityTimeout;

    /**
     * The duration (in seconds) for which the call waits for a message to arrive in the queue before returning. If a
     * message is available, the call returns sooner than `WaitTimeSeconds`. If no messages are available and the wait time
     * expires, the call returns successfully with an empty list of messages.
     *
     * ! To avoid HTTP errors, ensure that the HTTP response timeout for `ReceiveMessage` requests is longer than the
     * ! `WaitTimeSeconds` parameter. For example, with the Java SDK, you can set HTTP transport settings using the
     * ! NettyNioAsyncHttpClient [^1] for asynchronous clients, or the  ApacheHttpClient [^2] for synchronous clients.
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
     * - If a caller of the `ReceiveMessage` action doesn't provide a `ReceiveRequestAttemptId`, Amazon SQS generates a
     *   `ReceiveRequestAttemptId`.
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
     *   `MessageGroupId` as long as it is also visible.
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
     *   AttributeNames?: list<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->attributeNames = $input['AttributeNames'] ?? null;
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
     *   AttributeNames?: list<MessageSystemAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     *   '@region'?: string|null,
     * }|ReceiveMessageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<MessageSystemAttributeName::*>
     */
    public function getAttributeNames(): array
    {
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
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'ReceiveMessage', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param list<MessageSystemAttributeName::*> $value
     */
    public function setAttributeNames(array $value): self
    {
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
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null !== $v = $this->attributeNames) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                if (!MessageSystemAttributeName::exists($mapValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "AttributeName" for "%s". The value "%s" is not a valid "MessageSystemAttributeName".', __CLASS__, $mapValue));
                }
                $payload["AttributeName.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->messageAttributeNames) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["MessageAttributeName.$index"] = $mapValue;
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
