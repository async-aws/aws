<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\QueueAttributeName;

class ReceiveMessageRequest implements Input
{
    /**
     * The URL of the Amazon SQS queue from which messages are received.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * A list of attributes that need to be returned along with each message. These attributes include:.
     *
     * @var list<QueueAttributeName::*>
     */
    private $AttributeNames;

    /**
     * The name of the message attribute, where *N* is the index.
     *
     * @var string[]
     */
    private $MessageAttributeNames;

    /**
     * The maximum number of messages to return. Amazon SQS never returns more messages than this value (however, fewer
     * messages might be returned). Valid values: 1 to 10. Default: 1.
     *
     * @var int|null
     */
    private $MaxNumberOfMessages;

    /**
     * The duration (in seconds) that the received messages are hidden from subsequent retrieve requests after being
     * retrieved by a `ReceiveMessage` request.
     *
     * @var int|null
     */
    private $VisibilityTimeout;

    /**
     * The duration (in seconds) for which the call waits for a message to arrive in the queue before returning. If a
     * message is available, the call returns sooner than `WaitTimeSeconds`. If no messages are available and the wait time
     * expires, the call returns successfully with an empty list of messages.
     *
     * @var int|null
     */
    private $WaitTimeSeconds;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $ReceiveRequestAttemptId;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   AttributeNames?: list<\AsyncAws\Sqs\Enum\QueueAttributeName::*>,
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->AttributeNames = $input['AttributeNames'] ?? [];
        $this->MessageAttributeNames = $input['MessageAttributeNames'] ?? [];
        $this->MaxNumberOfMessages = $input['MaxNumberOfMessages'] ?? null;
        $this->VisibilityTimeout = $input['VisibilityTimeout'] ?? null;
        $this->WaitTimeSeconds = $input['WaitTimeSeconds'] ?? null;
        $this->ReceiveRequestAttemptId = $input['ReceiveRequestAttemptId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<QueueAttributeName::*>
     */
    public function getAttributeNames(): array
    {
        return $this->AttributeNames;
    }

    public function getMaxNumberOfMessages(): ?int
    {
        return $this->MaxNumberOfMessages;
    }

    /**
     * @return string[]
     */
    public function getMessageAttributeNames(): array
    {
        return $this->MessageAttributeNames;
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function getReceiveRequestAttemptId(): ?string
    {
        return $this->ReceiveRequestAttemptId;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->VisibilityTimeout;
    }

    public function getWaitTimeSeconds(): ?int
    {
        return $this->WaitTimeSeconds;
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
     * @param list<QueueAttributeName::*> $value
     */
    public function setAttributeNames(array $value): self
    {
        $this->AttributeNames = $value;

        return $this;
    }

    public function setMaxNumberOfMessages(?int $value): self
    {
        $this->MaxNumberOfMessages = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setMessageAttributeNames(array $value): self
    {
        $this->MessageAttributeNames = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function setReceiveRequestAttemptId(?string $value): self
    {
        $this->ReceiveRequestAttemptId = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->VisibilityTimeout = $value;

        return $this;
    }

    public function setWaitTimeSeconds(?int $value): self
    {
        $this->WaitTimeSeconds = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;

        $index = 0;
        foreach ($this->AttributeNames as $mapValue) {
            ++$index;
            if (!QueueAttributeName::exists($mapValue)) {
                throw new InvalidArgument(sprintf('Invalid parameter "AttributeName" for "%s". The value "%s" is not a valid "QueueAttributeName".', __CLASS__, $mapValue));
            }
            $payload["AttributeName.$index"] = $mapValue;
        }

        $index = 0;
        foreach ($this->MessageAttributeNames as $mapValue) {
            ++$index;
            $payload["MessageAttributeName.$index"] = $mapValue;
        }

        if (null !== $v = $this->MaxNumberOfMessages) {
            $payload['MaxNumberOfMessages'] = $v;
        }
        if (null !== $v = $this->VisibilityTimeout) {
            $payload['VisibilityTimeout'] = $v;
        }
        if (null !== $v = $this->WaitTimeSeconds) {
            $payload['WaitTimeSeconds'] = $v;
        }
        if (null !== $v = $this->ReceiveRequestAttemptId) {
            $payload['ReceiveRequestAttemptId'] = $v;
        }

        return $payload;
    }
}
