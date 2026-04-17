<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * Describes a tag.
 */
final class Tag
{
    /**
     * The key of the tag.
     *
     * Constraints: Tag keys are case-sensitive and accept a maximum of 127 Unicode characters. May not begin with `aws:`.
     *
     * @var string|null
     */
    private $key;

    /**
     * The value of the tag.
     *
     * Constraints: Tag values are case-sensitive and accept a maximum of 256 Unicode characters.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * }|Tag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
