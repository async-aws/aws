<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\TransitionStorageClass;

/**
 * Specifies when an object transitions to a specified storage class. For more information about Amazon S3 lifecycle
 * configuration rules, see Transitioning Objects Using Amazon S3 Lifecycle [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/lifecycle-transition-general-considerations.html
 */
final class Transition
{
    /**
     * Indicates when objects are transitioned to the specified storage class. The date value must be in ISO 8601 format.
     * The time is always midnight UTC.
     *
     * @var \DateTimeImmutable|null
     */
    private $date;

    /**
     * Indicates the number of days after creation when objects are transitioned to the specified storage class. If the
     * specified storage class is `INTELLIGENT_TIERING`, `GLACIER_IR`, `GLACIER`, or `DEEP_ARCHIVE`, valid values are `0` or
     * positive integers. If the specified storage class is `STANDARD_IA` or `ONEZONE_IA`, valid values are positive
     * integers greater than `30`. Be aware that some storage classes have a minimum storage duration and that you're
     * charged for transitioning objects before their minimum storage duration. For more information, see Constraints and
     * considerations for transitions [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/lifecycle-transition-general-considerations.html#lifecycle-configuration-constraints
     *
     * @var int|null
     */
    private $days;

    /**
     * The storage class to which you want the object to transition.
     *
     * @var TransitionStorageClass::*|null
     */
    private $storageClass;

    /**
     * @param array{
     *   Date?: \DateTimeImmutable|null,
     *   Days?: int|null,
     *   StorageClass?: TransitionStorageClass::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->date = $input['Date'] ?? null;
        $this->days = $input['Days'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
    }

    /**
     * @param array{
     *   Date?: \DateTimeImmutable|null,
     *   Days?: int|null,
     *   StorageClass?: TransitionStorageClass::*|null,
     * }|Transition $input
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

    /**
     * @return TransitionStorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
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
        if (null !== $v = $this->storageClass) {
            if (!TransitionStorageClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "StorageClass" for "%s". The value "%s" is not a valid "TransitionStorageClass".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('StorageClass', $v));
        }
    }
}
