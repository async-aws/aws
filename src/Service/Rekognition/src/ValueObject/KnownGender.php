<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\KnownGenderType;

/**
 * The known gender identity for the celebrity that matches the provided ID. The known gender identity can be Male,
 * Female, Nonbinary, or Unlisted.
 */
final class KnownGender
{
    /**
     * A string value of the KnownGender info about the Celebrity.
     *
     * @var KnownGenderType::*|string|null
     */
    private $type;

    /**
     * @param array{
     *   Type?: null|KnownGenderType::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   Type?: null|KnownGenderType::*|string,
     * }|KnownGender $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KnownGenderType::*|string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
