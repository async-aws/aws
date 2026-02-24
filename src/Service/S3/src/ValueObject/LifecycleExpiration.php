<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container for the expiration for the lifecycle of the object.
 *
 * For more information see, Managing your storage lifecycle [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lifecycle-mgmt.html
 */
final class LifecycleExpiration
{
    /**
     * Indicates at what date the object is to be moved or deleted. The date value must conform to the ISO 8601 format. The
     * time is always midnight UTC.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var \DateTimeImmutable|null
     */
    private $date;

    /**
     * Indicates the lifetime, in days, of the objects that are subject to the rule. The value must be a non-zero positive
     * integer.
     *
     * @var int|null
     */
    private $days;

    /**
     * Indicates whether Amazon S3 will remove a delete marker with no noncurrent versions. If set to true, the delete
     * marker will be expired; if set to false the policy takes no action. This cannot be specified with Days or Date in a
     * Lifecycle Expiration Policy.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var bool|null
     */
    private $expiredObjectDeleteMarker;

    /**
     * @param array{
     *   Date?: \DateTimeImmutable|null,
     *   Days?: int|null,
     *   ExpiredObjectDeleteMarker?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->date = $input['Date'] ?? null;
        $this->days = $input['Days'] ?? null;
        $this->expiredObjectDeleteMarker = $input['ExpiredObjectDeleteMarker'] ?? null;
    }

    /**
     * @param array{
     *   Date?: \DateTimeImmutable|null,
     *   Days?: int|null,
     *   ExpiredObjectDeleteMarker?: bool|null,
     * }|LifecycleExpiration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function getExpiredObjectDeleteMarker(): ?bool
    {
        return $this->expiredObjectDeleteMarker;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->date) {
            $node->appendChild($document->createElement('Date', $v->format(\DateTimeInterface::ATOM)));
        }
        if (null !== $v = $this->days) {
            $node->appendChild($document->createElement('Days', (string) $v));
        }
        if (null !== $v = $this->expiredObjectDeleteMarker) {
            $node->appendChild($document->createElement('ExpiredObjectDeleteMarker', $v ? 'true' : 'false'));
        }
    }
}
