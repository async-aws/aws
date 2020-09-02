<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;
use AsyncAws\Sqs\ValueObject\MessageSystemAttributeValue;

final class SendMessageRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue to which a message is sent.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * The message to send. The minimum size is one character. The maximum size is 256 KB.
     *
     * @required
     *
     * @var string|null
     */
    private $MessageBody;

    /**
     * The length of time, in seconds, for which to delay a specific message. Valid values: 0 to 900. Maximum: 15 minutes.
     * Messages with a positive `DelaySeconds` value become available for processing after the delay period is finished. If
     * you don't specify a value, the default value for the queue applies.
     *
     * @var int|null
     */
    private $DelaySeconds;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS Message
     * Attributes in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-metadata.html#sqs-message-attributes
     *
     * @var array<string, MessageAttributeValue>|null
     */
    private $MessageAttributes;

    /**
     * The message system attribute to send. Each message system attribute consists of a `Name`, `Type`, and `Value`.
     *
     * @var null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>
     */
    private $MessageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $MessageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $MessageGroupId;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   MessageBody?: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: array<string, MessageAttributeValue>,
     *   MessageSystemAttributes?: array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->MessageBody = $input['MessageBody'] ?? null;
        $this->DelaySeconds = $input['DelaySeconds'] ?? null;

        $this->MessageAttributes = [];
        foreach ($input['MessageAttributes'] ?? [] as $key => $item) {
            $this->MessageAttributes[$key] = MessageAttributeValue::create($item);
        }

        $this->MessageSystemAttributes = [];
        foreach ($input['MessageSystemAttributes'] ?? [] as $key => $item) {
            $this->MessageSystemAttributes[$key] = MessageSystemAttributeValue::create($item);
        }
        $this->MessageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->MessageGroupId = $input['MessageGroupId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelaySeconds(): ?int
    {
        return $this->DelaySeconds;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes ?? [];
    }

    public function getMessageBody(): ?string
    {
        return $this->MessageBody;
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->MessageDeduplicationId;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->MessageGroupId;
    }

    /**
     * @return array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>
     */
    public function getMessageSystemAttributes(): array
    {
        return $this->MessageSystemAttributes ?? [];
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
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
        $body = http_build_query(['Action' => 'SendMessage', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDelaySeconds(?int $value): self
    {
        $this->DelaySeconds = $value;

        return $this;
    }

    /**
     * @param array<string, MessageAttributeValue> $value
     */
    public function setMessageAttributes(array $value): self
    {
        $this->MessageAttributes = $value;

        return $this;
    }

    public function setMessageBody(?string $value): self
    {
        $this->MessageBody = $value;

        return $this;
    }

    public function setMessageDeduplicationId(?string $value): self
    {
        $this->MessageDeduplicationId = $value;

        return $this;
    }

    public function setMessageGroupId(?string $value): self
    {
        $this->MessageGroupId = $value;

        return $this;
    }

    /**
     * @param array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue> $value
     */
    public function setMessageSystemAttributes(array $value): self
    {
        $this->MessageSystemAttributes = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->MessageBody) {
            throw new InvalidArgument(sprintf('Missing parameter "MessageBody" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['MessageBody'] = $v;
        if (null !== $v = $this->DelaySeconds) {
            $payload['DelaySeconds'] = $v;
        }
        if (null !== $v = $this->MessageAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["MessageAttribute.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageAttribute.$index.Value.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->MessageSystemAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                if (!MessageSystemAttributeNameForSends::exists($mapKey)) {
                    throw new InvalidArgument(sprintf('Invalid key for "%s". The value "%s" is not a valid "MessageSystemAttributeNameForSends".', __CLASS__, $mapKey));
                }
                ++$index;
                $payload["MessageSystemAttribute.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageSystemAttribute.$index.Value.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->MessageDeduplicationId) {
            $payload['MessageDeduplicationId'] = $v;
        }
        if (null !== $v = $this->MessageGroupId) {
            $payload['MessageGroupId'] = $v;
        }

        return $payload;
    }
}
