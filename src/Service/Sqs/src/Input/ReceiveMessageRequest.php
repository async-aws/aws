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
     * @var int|null
     */
    private $waitTimeSeconds;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
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
     *   @region?: string,
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
