<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Route53\Enum\ChangeStatus;

/**
 * A complex type that contains information about changes made to your hosted zone.
 * This element contains an ID that you use when performing a GetChange action to get detailed information about the
 * change.
 *
 * @see https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetChange.html
 */
final class ChangeInfo
{
    /**
     * The ID of the request.
     */
    private $id;

    /**
     * The current state of the request. `PENDING` indicates that this request has not yet been applied to all Amazon Route
     * 53 DNS servers.
     */
    private $status;

    /**
     * The date and time that the change request was submitted in ISO 8601 format and Coordinated Universal Time (UTC). For
     * example, the value `2017-03-27T17:48:16.751Z` represents March 27, 2017 at 17:48:16.751 UTC.
     *
     * @see https://en.wikipedia.org/wiki/ISO_8601
     */
    private $submittedAt;

    /**
     * A complex type that describes change information about changes made to your hosted zone.
     */
    private $comment;

    /**
     * @param array{
     *   Id: string,
     *   Status: ChangeStatus::*,
     *   SubmittedAt: \DateTimeImmutable,
     *   Comment?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->submittedAt = $input['SubmittedAt'] ?? null;
        $this->comment = $input['Comment'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ChangeStatus::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSubmittedAt(): \DateTimeImmutable
    {
        return $this->submittedAt;
    }
}
