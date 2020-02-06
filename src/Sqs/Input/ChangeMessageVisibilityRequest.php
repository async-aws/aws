<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class ChangeMessageVisibilityRequest
{
    /**
     * The URL of the Amazon SQS queue whose message's visibility is changed.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * The receipt handle associated with the message whose visibility timeout is changed. This parameter is returned by the
     * `ReceiveMessage` action.
     *
     * @required
     *
     * @var string|null
     */
    private $ReceiptHandle;

    /**
     * The new value for the message's visibility timeout (in seconds). Values values: `0` to `43200`. Maximum: 12 hours.
     *
     * @required
     *
     * @var int|null
     */
    private $VisibilityTimeout;

    /**
     * @param array{
     *   QueueUrl: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->ReceiptHandle = $input['ReceiptHandle'] ?? null;
        $this->VisibilityTimeout = $input['VisibilityTimeout'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->ReceiptHandle;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->VisibilityTimeout;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'ChangeMessageVisibility', 'Version' => '2012-11-05'];
        if (null !== $this->QueueUrl) {
            $payload['QueueUrl'] = $this->QueueUrl;
        }
        if (null !== $this->ReceiptHandle) {
            $payload['ReceiptHandle'] = $this->ReceiptHandle;
        }
        if (null !== $this->VisibilityTimeout) {
            $payload['VisibilityTimeout'] = $this->VisibilityTimeout;
        }

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/';
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function setReceiptHandle(?string $value): self
    {
        $this->ReceiptHandle = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->VisibilityTimeout = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['QueueUrl', 'ReceiptHandle', 'VisibilityTimeout'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
