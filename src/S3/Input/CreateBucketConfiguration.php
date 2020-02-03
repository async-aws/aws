<?php

namespace AsyncAws\S3\Input;

class CreateBucketConfiguration
{
    /**
     * Specifies the Region where the bucket will be created. If you don't specify a Region, the bucket is created in the US
     * East (N. Virginia) Region (us-east-1).
     *
     * @var string|null
     */
    private $LocationConstraint;

    /**
     * @param array{
     *   LocationConstraint?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->LocationConstraint = $input['LocationConstraint'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
