<?php

namespace AsyncAws\XRay\ValueObject;

/**
 * Information about a segment that failed processing.
 */
final class UnprocessedTraceSegment
{
    /**
     * The segment's ID.
     */
    private $id;

    /**
     * The error that caused processing to fail.
     */
    private $errorCode;

    /**
     * The error message.
     */
    private $message;

    /**
     * @param array{
     *   Id?: null|string,
     *   ErrorCode?: null|string,
     *   Message?: null|string,
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
     *   Id?: null|string,
     *   ErrorCode?: null|string,
     *   Message?: null|string,
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
