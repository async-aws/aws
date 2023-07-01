<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses a receipt handle and an entry ID for each message in `ChangeMessageVisibilityBatch.`.
 */
final class ChangeMessageVisibilityBatchRequestEntry
{
    /**
     * An identifier for this particular receipt handle used to communicate the result.
     *
     * > The `Id`s of a batch request need to be unique within a request.
     * >
     * > This identifier can have up to 80 characters. The following characters are accepted: alphanumeric characters,
     * > hyphens(-), and underscores (_).
     */
    private $id;

    /**
     * A receipt handle.
     */
    private $receiptHandle;

    /**
     * The new value (in seconds) for the message's visibility timeout.
     */
    private $visibilityTimeout;

    /**
     * @param array{
     *   Id: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->receiptHandle = $input['ReceiptHandle'] ?? $this->throwException(new InvalidArgument('Missing required field "ReceiptHandle".'));
        $this->visibilityTimeout = $input['VisibilityTimeout'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   ReceiptHandle: string,
     *   VisibilityTimeout?: null|int,
     * }|ChangeMessageVisibilityBatchRequestEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getReceiptHandle(): string
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
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->id) {
            throw new InvalidArgument(sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Id'] = $v;
        if (null === $v = $this->receiptHandle) {
            throw new InvalidArgument(sprintf('Missing parameter "ReceiptHandle" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReceiptHandle'] = $v;
        if (null !== $v = $this->visibilityTimeout) {
            $payload['VisibilityTimeout'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
