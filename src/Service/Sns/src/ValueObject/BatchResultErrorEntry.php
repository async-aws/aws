<?php

namespace AsyncAws\Sns\ValueObject;

/**
 * Gives a detailed description of failed messages in the batch.
 */
final class BatchResultErrorEntry
{
    /**
     * The `Id` of an entry in a batch request.
     */
    private $id;

    /**
     * An error code representing why the action failed on this entry.
     */
    private $code;

    /**
     * A message explaining why the action failed on this entry.
     */
    private $message;

    /**
     * Specifies whether the error happened due to the caller of the batch API action.
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
        $this->id = $input['Id'] ?? null;
        $this->code = $input['Code'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->senderFault = $input['SenderFault'] ?? null;
    }

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
}
