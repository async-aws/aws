<?php

namespace AsyncAws\Comprehend\ValueObject;

use AsyncAws\Comprehend\Enum\InvalidRequestDetailReason;

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
