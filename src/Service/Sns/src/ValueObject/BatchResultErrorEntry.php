<?php

namespace AsyncAws\Sns\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Gives a detailed description of failed messages in the batch.
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
     * Specifies whether the error happened due to the caller of the batch API action.
     *
     * @var bool
     */
    private $senderFault;

    /**
     * @param array{
     *   Id: string,
     *   Code: string,
     *   Message?: null|string,
     *   SenderFault: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->code = $input['Code'] ?? $this->throwException(new InvalidArgument('Missing required field "Code".'));
        $this->message = $input['Message'] ?? null;
        $this->senderFault = $input['SenderFault'] ?? $this->throwException(new InvalidArgument('Missing required field "SenderFault".'));
    }

    /**
     * @param array{
     *   Id: string,
     *   Code: string,
     *   Message?: null|string,
     *   SenderFault: bool,
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
