<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses a receipt handle and an identifier for it.
 */
final class DeleteMessageBatchRequestEntry
{
    /**
     * The identifier for this particular receipt handle. This is used to communicate the result.
     *
     * > The `Id`s of a batch request need to be unique within a request.
     * >
     * > This identifier can have up to 80 characters. The following characters are accepted: alphanumeric characters,
     * > hyphens(-), and underscores (_).
     *
     * @var string
     */
    private $id;

    /**
     * A receipt handle.
     *
     * @var string
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
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->receiptHandle = $input['ReceiptHandle'] ?? $this->throwException(new InvalidArgument('Missing required field "ReceiptHandle".'));
    }

    /**
     * @param array{
     *   Id: string,
     *   ReceiptHandle: string,
     * }|DeleteMessageBatchRequestEntry $input
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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->id;
        $payload['Id'] = $v;
        $v = $this->receiptHandle;
        $payload['ReceiptHandle'] = $v;

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
