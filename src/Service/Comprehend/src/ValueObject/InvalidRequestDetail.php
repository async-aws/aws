<?php

namespace AsyncAws\Comprehend\ValueObject;

use AsyncAws\Comprehend\Enum\InvalidRequestDetailReason;

/**
 * Provides additional detail about why the request failed:.
 *
 * - Document size is too large - Check the size of your file and resubmit the request.
 * - Document type is not supported - Check the file type and resubmit the request.
 * - Too many pages in the document - Check the number of pages in your file and resubmit the request.
 * - Access denied to Amazon Textract - Verify that your account has permission to use Amazon Textract API operations
 *   and resubmit the request.
 */
final class InvalidRequestDetail
{
    /**
     * Reason code is `INVALID_DOCUMENT`.
     */
    private $reason;

    /**
     * @param array{
     *   Reason?: null|InvalidRequestDetailReason::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reason = $input['Reason'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InvalidRequestDetailReason::*|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
