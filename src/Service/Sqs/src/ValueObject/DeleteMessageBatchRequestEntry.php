<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses a receipt handle and an identifier for it.
 */
final class DeleteMessageBatchRequestEntry
{
    /**
     * An identifier for this particular receipt handle. This is used to communicate the result.
     */
    private $id;

    /**
     * A receipt handle.
     */
    private $receiptHandle;

    /**
     * @param array{
     *   Id: string,
     *   ReceiptHandle: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
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

        return $payload;
    }
}
