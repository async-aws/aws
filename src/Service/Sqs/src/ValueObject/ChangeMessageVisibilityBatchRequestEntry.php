<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses a receipt handle and an entry id for each message in `ChangeMessageVisibilityBatch.`.
 *
 * ! All of the following list parameters must be prefixed with `ChangeMessageVisibilityBatchRequestEntry.n`, where `n`
 * ! is an integer value starting with `1`. For example, a parameter list for this action might look like this:
 *
 * `&amp;ChangeMessageVisibilityBatchRequestEntry.1.Id=change_visibility_msg_2`
 * `&amp;ChangeMessageVisibilityBatchRequestEntry.1.ReceiptHandle=your_receipt_handle`
 * `&amp;ChangeMessageVisibilityBatchRequestEntry.1.VisibilityTimeout=45`
 */
final class ChangeMessageVisibilityBatchRequestEntry
{
    /**
     * An identifier for this particular receipt handle used to communicate the result.
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
        $this->id = $input['Id'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
        $this->visibilityTimeout = $input['VisibilityTimeout'] ?? null;
    }

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
}
