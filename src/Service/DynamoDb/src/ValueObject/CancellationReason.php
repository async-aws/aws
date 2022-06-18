<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * An ordered list of errors for each item in the request which caused the transaction to get cancelled. The values of
 * the list are ordered according to the ordering of the `TransactWriteItems` request parameter. If no error occurred
 * for the associated item an error with a Null code and Null message will be present.
 */
final class CancellationReason
{
    /**
     * Item in the request which caused the transaction to get cancelled.
     */
    private $item;

    /**
     * Status code for the result of the cancelled transaction.
     */
    private $code;

    /**
     * Cancellation reason message description.
     */
    private $message;

    /**
     * @param array{
     *   Item?: null|array<string, AttributeValue>,
     *   Code?: null|string,
     *   Message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->item = isset($input['Item']) ? array_map([AttributeValue::class, 'create'], $input['Item']) : null;
        $this->code = $input['Code'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getItem(): array
    {
        return $this->item ?? [];
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
