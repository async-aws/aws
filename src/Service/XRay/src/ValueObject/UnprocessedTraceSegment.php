<?php

namespace AsyncAws\XRay\ValueObject;

/**
 * Information about a segment that failed processing.
 */
final class UnprocessedTraceSegment
{
    /**
     * The segment's ID.
     *
     * @var string|null
     */
    private $id;

    /**
     * The error that caused processing to fail.
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
     *   Id?: string|null,
     *   ErrorCode?: string|null,
     *   Message?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    /**
     * @param array{
     *   Id?: string|null,
     *   ErrorCode?: string|null,
     *   Message?: string|null,
     * }|UnprocessedTraceSegment $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
