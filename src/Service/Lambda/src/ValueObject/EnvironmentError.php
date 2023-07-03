<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Error messages for environment variables that couldn't be applied.
 */
final class EnvironmentError
{
    /**
     * The error code.
     *
     * @var string|null
     */
    private $errorCode;

    /**
     * The error message.
     *
     * @var string|null
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

    /**
     * @param array{
     *   ErrorCode?: null|string,
     *   Message?: null|string,
     * }|EnvironmentError $input
     */
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
