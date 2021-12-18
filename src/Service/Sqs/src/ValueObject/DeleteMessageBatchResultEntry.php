<?php

namespace AsyncAws\Sqs\ValueObject;

/**
 * Encloses the `Id` of an entry in `DeleteMessageBatch.`.
 */
final class DeleteMessageBatchResultEntry
{
    /**
     * Represents a successfully deleted message.
     */
    private $id;

    /**
     * @param array{
     *   Id: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }
}
