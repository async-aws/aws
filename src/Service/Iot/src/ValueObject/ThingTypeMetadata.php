<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The ThingTypeMetadata contains additional information about the thing type including: creation date and time, a value
 * indicating whether the thing type is deprecated, and a date and time when it was deprecated.
 */
final class ThingTypeMetadata
{
    /**
     * Whether the thing type is deprecated. If **true**, no new things could be associated with this type.
     */
    private $deprecated;

    /**
     * The date and time when the thing type was deprecated.
     */
    private $deprecationDate;

    /**
     * The date and time when the thing type was created.
     */
    private $creationDate;

    /**
     * @param array{
     *   deprecated?: null|bool,
     *   deprecationDate?: null|\DateTimeImmutable,
     *   creationDate?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deprecated = $input['deprecated'] ?? null;
        $this->deprecationDate = $input['deprecationDate'] ?? null;
        $this->creationDate = $input['creationDate'] ?? null;
    }

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
