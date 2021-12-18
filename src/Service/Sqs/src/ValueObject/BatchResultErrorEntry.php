<?php

namespace AsyncAws\Sqs\ValueObject;

/**
 * Gives a detailed description of the result of an action on each entry in the request.
 */
final class BatchResultErrorEntry
{
    /**
     * The `Id` of an entry in a batch request.
     */
    private $id;

    /**
     * Specifies whether the error happened due to the caller of the batch API action.
     */
    private $senderFault;

    /**
     * An error code representing why the action failed on this entry.
     */
    private $code;

    /**
     * A message explaining why the action failed on this entry.
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
        $this->id = $input['Id'] ?? null;
        $this->senderFault = $input['SenderFault'] ?? null;
        $this->code = $input['Code'] ?? null;
        $this->message = $input['Message'] ?? null;
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
