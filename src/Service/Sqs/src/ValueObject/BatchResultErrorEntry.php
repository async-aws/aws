<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Gives a detailed description of the result of an action on each entry in the request.
 */
final class BatchResultErrorEntry
{
    /**
     * The `Id` of an entry in a batch request.
     *
     * @var string
     */
    private $id;

    /**
     * Specifies whether the error happened due to the caller of the batch API action.
     *
     * @var bool
     */
    private $senderFault;

    /**
     * An error code representing why the action failed on this entry.
     *
     * @var string
     */
    private $code;

    /**
     * A message explaining why the action failed on this entry.
     *
     * @var string|null
     */
    private $message;

    /**
     * @param array{
     *   Id: string,
     *   SenderFault: bool,
     *   Code: string,
     *   Message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->senderFault = $input['SenderFault'] ?? $this->throwException(new InvalidArgument('Missing required field "SenderFault".'));
        $this->code = $input['Code'] ?? $this->throwException(new InvalidArgument('Missing required field "Code".'));
        $this->message = $input['Message'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   SenderFault: bool,
     *   Code: string,
     *   Message?: null|string,
     * }|BatchResultErrorEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getSenderFault(): bool
    {
        return $this->senderFault;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
