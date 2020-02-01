<?php

namespace AsyncAws\S3\Input;

class CreateBucketConfiguration
{
    /**
     * @var string|null
     */
    private $LocationConstraint;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   LocationConstraint?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->LocationConstraint = $input["LocationConstraint"] ?? null;
    }

    public function getLocationConstraint(): ?string
    {
        return $this->LocationConstraint;
    }

    public function setLocationConstraint(?string $value): self
    {
        $this->LocationConstraint = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
