<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\BucketLocationConstraint;

class CreateBucketConfiguration
{
    /**
     * Specifies the Region where the bucket will be created. If you don't specify a Region, the bucket is created in the US
     * East (N. Virginia) Region (us-east-1).
     */
    private $LocationConstraint;

    /**
     * @param array{
     *   LocationConstraint?: null|\AsyncAws\S3\Enum\BucketLocationConstraint::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->LocationConstraint = $input['LocationConstraint'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return BucketLocationConstraint::*|null
     */
    public function getLocationConstraint(): ?string
    {
        return $this->LocationConstraint;
    }

    public function validate(): void
    {
        if (null !== $this->LocationConstraint) {
            if (!BucketLocationConstraint::exists($this->LocationConstraint)) {
                throw new InvalidArgument(sprintf('Invalid parameter "LocationConstraint" when validating the "%s". The value "%s" is not a valid "BucketLocationConstraint".', __CLASS__, $this->LocationConstraint));
            }
        }
    }
}
