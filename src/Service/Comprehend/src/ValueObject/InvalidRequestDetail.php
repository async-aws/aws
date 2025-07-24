<?php

namespace AsyncAws\Comprehend\ValueObject;

use AsyncAws\Comprehend\Enum\InvalidRequestDetailReason;

/**
 * Provides additional detail about why the request failed.
 */
final class InvalidRequestDetail
{
    /**
     * Reason codes include the following values:
     *
     * - DOCUMENT_SIZE_EXCEEDED - Document size is too large. Check the size of your file and resubmit the request.
     * - UNSUPPORTED_DOC_TYPE - Document type is not supported. Check the file type and resubmit the request.
     * - PAGE_LIMIT_EXCEEDED - Too many pages in the document. Check the number of pages in your file and resubmit the
     *   request.
     * - TEXTRACT_ACCESS_DENIED - Access denied to Amazon Textract. Verify that your account has permission to use Amazon
     *   Textract API operations and resubmit the request.
     * - NOT_TEXTRACT_JSON - Document is not Amazon Textract JSON format. Verify the format and resubmit the request.
     * - MISMATCHED_TOTAL_PAGE_COUNT - Check the number of pages in your file and resubmit the request.
     * - INVALID_DOCUMENT - Invalid document. Check the file and resubmit the request.
     *
     * @var InvalidRequestDetailReason::*|string|null
     */
    private $reason;

    /**
     * @param array{
     *   Reason?: null|InvalidRequestDetailReason::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reason = $input['Reason'] ?? null;
    }

    /**
     * @param array{
     *   Reason?: null|InvalidRequestDetailReason::*|string,
     * }|InvalidRequestDetail $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InvalidRequestDetailReason::*|string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }
}
