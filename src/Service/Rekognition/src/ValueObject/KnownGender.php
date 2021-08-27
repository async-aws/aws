<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\KnownGenderType;

/**
 * Retrieves the known gender for the celebrity.
 */
final class KnownGender
{
    /**
     * A string value of the KnownGender info about the Celebrity.
     */
    private $type;

    /**
     * @param array{
     *   Type?: null|KnownGenderType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KnownGenderType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
