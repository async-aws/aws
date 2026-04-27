<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class StartMessageMoveTaskRequest extends Input
{
    /**
     * The ARN of the queue that contains the messages to be moved to another queue. Currently, only ARNs of dead-letter
     * queues (DLQs) whose sources are other Amazon SQS queues are accepted. DLQs whose sources are non-SQS queues, such as
     * Lambda or Amazon SNS topics, are not currently supported.
     *
     * @required
     *
     * @var string|null
     */
    private $sourceArn;

    /**
     * The ARN of the queue that receives the moved messages. You can use this field to specify the destination queue where
     * you would like to redrive messages. If this field is left blank, the messages will be redriven back to their
     * respective original source queues.
     *
     * @var string|null
     */
    private $destinationArn;

    /**
     * The number of messages to be moved per second (the message movement rate). You can use this field to define a fixed
     * message movement rate. The maximum value for messages per second is 500. If this field is left blank, the system will
     * optimize the rate based on the queue message backlog size, which may vary throughout the duration of the message
     * movement task.
     *
     * @var int|null
     */
    private $maxNumberOfMessagesPerSecond;

    /**
     * @param array{
     *   SourceArn?: string,
     *   DestinationArn?: string|null,
     *   MaxNumberOfMessagesPerSecond?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->sourceArn = $input['SourceArn'] ?? null;
        $this->destinationArn = $input['DestinationArn'] ?? null;
        $this->maxNumberOfMessagesPerSecond = $input['MaxNumberOfMessagesPerSecond'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SourceArn?: string,
     *   DestinationArn?: string|null,
     *   MaxNumberOfMessagesPerSecond?: int|null,
     *   '@region'?: string|null,
     * }|StartMessageMoveTaskRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestinationArn(): ?string
    {
        return $this->destinationArn;
    }

    public function getMaxNumberOfMessagesPerSecond(): ?int
    {
        return $this->maxNumberOfMessagesPerSecond;
    }

    public function getSourceArn(): ?string
    {
        return $this->sourceArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.StartMessageMoveTask',
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

    public function setDestinationArn(?string $value): self
    {
        $this->destinationArn = $value;

        return $this;
    }

    public function setMaxNumberOfMessagesPerSecond(?int $value): self
    {
        $this->maxNumberOfMessagesPerSecond = $value;

        return $this;
    }

    public function setSourceArn(?string $value): self
    {
        $this->sourceArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sourceArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "SourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SourceArn'] = $v;
        if (null !== $v = $this->destinationArn) {
            $payload['DestinationArn'] = $v;
        }
        if (null !== $v = $this->maxNumberOfMessagesPerSecond) {
            $payload['MaxNumberOfMessagesPerSecond'] = $v;
        }

        return $payload;
    }
}
