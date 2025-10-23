<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Provides information about an Athena query error. The `AthenaError` feature provides standardized error information
 * to help you understand failed queries and take steps after a query failure occurs. `AthenaError` includes an
 * `ErrorCategory` field that specifies whether the cause of the failed query is due to system error, user error, or
 * other error.
 */
final class AthenaError
{
    /**
     * An integer value that specifies the category of a query failure error. The following list shows the category for each
     * integer value.
     *
     * **1** - System
     *
     * **2** - User
     *
     * **3** - Other
     *
     * @var int|null
     */
    private $errorCategory;

    /**
     * An integer value that provides specific information about an Athena query error. For the meaning of specific values,
     * see the Error Type Reference [^1] in the *Amazon Athena User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/error-reference.html#error-reference-error-type-reference
     *
     * @var int|null
     */
    private $errorType;

    /**
     * True if the query might succeed if resubmitted.
     *
     * @var bool|null
     */
    private $retryable;

    /**
     * Contains a short description of the error that occurred.
     *
     * @var string|null
     */
    private $errorMessage;

    /**
     * @param array{
     *   ErrorCategory?: int|null,
     *   ErrorType?: int|null,
     *   Retryable?: bool|null,
     *   ErrorMessage?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorCategory = $input['ErrorCategory'] ?? null;
        $this->errorType = $input['ErrorType'] ?? null;
        $this->retryable = $input['Retryable'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
    }

    /**
     * @param array{
     *   ErrorCategory?: int|null,
     *   ErrorType?: int|null,
     *   Retryable?: bool|null,
     *   ErrorMessage?: string|null,
     * }|AthenaError $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCategory(): ?int
    {
        return $this->errorCategory;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getErrorType(): ?int
    {
        return $this->errorType;
    }

    public function getRetryable(): ?bool
    {
        return $this->retryable;
    }
}
