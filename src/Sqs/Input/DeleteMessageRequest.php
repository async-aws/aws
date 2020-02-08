<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class DeleteMessageRequest
{
    /**
     * The URL of the Amazon SQS queue from which messages are deleted.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * The receipt handle associated with the message to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $ReceiptHandle;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   ReceiptHandle?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->ReceiptHandle = $input['ReceiptHandle'] ?? null;
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

    public function requestBody(): array
    {
        $payload = ['Action' => 'DeleteMessage', 'Version' => '2012-11-05'];
        if (null !== $this->QueueUrl) {
            $payload['QueueUrl'] = $this->QueueUrl;
        }
        if (null !== $this->ReceiptHandle) {
            $payload['ReceiptHandle'] = $this->ReceiptHandle;
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

    public function validate(): void
    {
        foreach (['QueueUrl', 'ReceiptHandle'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
