<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ChangeMessageVisibilityRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue whose message's visibility is changed.
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * The receipt handle associated with the message, whose visibility timeout is changed. This parameter is returned by
     * the `ReceiveMessage` action.
     *
     * @required
     *
     * @var string|null
     */
    private $receiptHandle;

    /**
     * The new value for the message's visibility timeout (in seconds). Values range: `0` to `43200`. Maximum: 12 hours.
     *
     * @required
     *
     * @var int|null
     */
    private $visibilityTimeout;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   ReceiptHandle?: string,
     *   VisibilityTimeout?: int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
        $this->visibilityTimeout = $input['VisibilityTimeout'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueUrl?: string,
     *   ReceiptHandle?: string,
     *   VisibilityTimeout?: int,
     *   '@region'?: string|null,
     * }|ChangeMessageVisibilityRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->receiptHandle;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->visibilityTimeout;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.ChangeMessageVisibility',
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

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    public function setReceiptHandle(?string $value): self
    {
        $this->receiptHandle = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->visibilityTimeout = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->receiptHandle) {
            throw new InvalidArgument(\sprintf('Missing parameter "ReceiptHandle" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReceiptHandle'] = $v;
        if (null === $v = $this->visibilityTimeout) {
            throw new InvalidArgument(\sprintf('Missing parameter "VisibilityTimeout" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['VisibilityTimeout'] = $v;

        return $payload;
    }
}
