<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The ThingTypeMetadata contains additional information about the thing type including: creation date and time, a value
 * indicating whether the thing type is deprecated, and a date and time when time was deprecated.
 */
final class ThingTypeMetadata
{
    /**
     * Whether the thing type is deprecated. If **true**, no new things could be associated with this type.
     *
     * @var bool|null
     */
    private $deprecated;

    /**
     * The date and time when the thing type was deprecated.
     *
     * @var \DateTimeImmutable|null
     */
    private $deprecationDate;

    /**
     * The date and time when the thing type was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDate;

    /**
     * @param array{
     *   deprecated?: bool|null,
     *   deprecationDate?: \DateTimeImmutable|null,
     *   creationDate?: \DateTimeImmutable|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deprecated = $input['deprecated'] ?? null;
        $this->deprecationDate = $input['deprecationDate'] ?? null;
        $this->creationDate = $input['creationDate'] ?? null;
    }

    /**
     * @param array{
     *   deprecated?: bool|null,
     *   deprecationDate?: \DateTimeImmutable|null,
     *   creationDate?: \DateTimeImmutable|null,
     * }|ThingTypeMetadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function getDeprecated(): ?bool
    {
        return $this->deprecated;
    }

    public function getDeprecationDate(): ?\DateTimeImmutable
    {
        return $this->deprecationDate;
    }
}
