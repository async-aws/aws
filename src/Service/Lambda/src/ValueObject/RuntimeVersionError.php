<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Error response when Lambda is unable to retrieve the runtime version for a function.
 */
final class RuntimeVersionError
{
    /**
     * The error code.
     */
    private $errorCode;

    /**
     * The error message.
     */
    private $message;

    /**
     * @param array{
     *   ErrorCode?: null|string,
     *   Message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
