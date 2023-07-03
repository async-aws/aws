<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses the `Id` of an entry in `ChangeMessageVisibilityBatch.`.
 */
final class ChangeMessageVisibilityBatchResultEntry
{
    /**
     * Represents a message whose visibility timeout has been changed successfully.
     *
     * @var string
     */
    private $id;

    /**
     * @param array{
     *   Id: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
    }

    /**
     * @param array{
     *   Id: string,
     * }|ChangeMessageVisibilityBatchResultEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
