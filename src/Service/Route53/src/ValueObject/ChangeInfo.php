<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Route53\Enum\ChangeStatus;

/**
 * A complex type that describes change information about changes made to your hosted zone.
 */
final class ChangeInfo
{
    /**
     * This element contains an ID that you use when performing a GetChange [^1] action to get detailed information about
     * the change.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/APIReference/API_GetChange.html
     *
     * @var string
     */
    private $id;

    /**
     * The current state of the request. `PENDING` indicates that this request has not yet been applied to all Amazon Route
     * 53 DNS servers.
     *
     * @var ChangeStatus::*|string
     */
    private $status;

    /**
     * The date and time that the change request was submitted in ISO 8601 format [^1] and Coordinated Universal Time (UTC).
     * For example, the value `2017-03-27T17:48:16.751Z` represents March 27, 2017 at 17:48:16.751 UTC.
     *
     * [^1]: https://en.wikipedia.org/wiki/ISO_8601
     *
     * @var \DateTimeImmutable
     */
    private $submittedAt;

    /**
     * A comment you can provide.
     *
     * @var string|null
     */
    private $comment;

    /**
     * @param array{
     *   Id: string,
     *   Status: ChangeStatus::*|string,
     *   SubmittedAt: \DateTimeImmutable,
     *   Comment?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->submittedAt = $input['SubmittedAt'] ?? $this->throwException(new InvalidArgument('Missing required field "SubmittedAt".'));
        $this->comment = $input['Comment'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   Status: ChangeStatus::*|string,
     *   SubmittedAt: \DateTimeImmutable,
     *   Comment?: null|string,
     * }|ChangeInfo $input
     */
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
     * @return ChangeStatus::*|string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSubmittedAt(): \DateTimeImmutable
    {
        return $this->submittedAt;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
