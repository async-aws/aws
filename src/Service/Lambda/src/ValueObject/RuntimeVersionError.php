<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Any error returned when the runtime version information for the function could not be retrieved.
 */
final class RuntimeVersionError
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
     *   ErrorCode?: string|null,
     *   Message?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    /**
     * @param array{
     *   ErrorCode?: string|null,
     *   Message?: string|null,
     * }|RuntimeVersionError $input
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
