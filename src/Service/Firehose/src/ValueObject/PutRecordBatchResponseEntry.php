<?php

namespace AsyncAws\Firehose\ValueObject;

/**
 * Contains the result for an individual record from a PutRecordBatch request. If the record is successfully added to
 * your delivery stream, it receives a record ID. If the record fails to be added to your delivery stream, the result
 * includes an error code and an error message.
 */
final class PutRecordBatchResponseEntry
{
    /**
     * The ID of the record.
     */
    private $recordId;

    /**
     * The error code for an individual record result.
     */
    private $errorCode;

    /**
     * The error message for an individual record result.
     */
    private $errorMessage;

    /**
     * @param array{
     *   RecordId?: null|string,
     *   ErrorCode?: null|string,
     *   ErrorMessage?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->recordId = $input['RecordId'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }
}
