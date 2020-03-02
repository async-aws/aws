<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\BucketLocationConstraint;

class CreateBucketConfiguration
{
    /**
     * Specifies the Region where the bucket will be created. If you don't specify a Region, the bucket is created in the US
     * East (N. Virginia) Region (us-east-1).
     *
     * @var BucketLocationConstraint::EU|BucketLocationConstraint::EU_WEST_1|BucketLocationConstraint::US_WEST_1|BucketLocationConstraint::US_WEST_2|BucketLocationConstraint::AP_SOUTH_1|BucketLocationConstraint::AP_SOUTHEAST_1|BucketLocationConstraint::AP_SOUTHEAST_2|BucketLocationConstraint::AP_NORTHEAST_1|BucketLocationConstraint::SA_EAST_1|BucketLocationConstraint::CN_NORTH_1|BucketLocationConstraint::EU_CENTRAL_1|null
     */
    private $LocationConstraint;

    /**
     * @param array{
     *   LocationConstraint?: \AsyncAws\S3\Enum\BucketLocationConstraint::EU|\AsyncAws\S3\Enum\BucketLocationConstraint::EU_WEST_1|\AsyncAws\S3\Enum\BucketLocationConstraint::US_WEST_1|\AsyncAws\S3\Enum\BucketLocationConstraint::US_WEST_2|\AsyncAws\S3\Enum\BucketLocationConstraint::AP_SOUTH_1|\AsyncAws\S3\Enum\BucketLocationConstraint::AP_SOUTHEAST_1|\AsyncAws\S3\Enum\BucketLocationConstraint::AP_SOUTHEAST_2|\AsyncAws\S3\Enum\BucketLocationConstraint::AP_NORTHEAST_1|\AsyncAws\S3\Enum\BucketLocationConstraint::SA_EAST_1|\AsyncAws\S3\Enum\BucketLocationConstraint::CN_NORTH_1|\AsyncAws\S3\Enum\BucketLocationConstraint::EU_CENTRAL_1,
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

    /**
     * @return BucketLocationConstraint::EU|BucketLocationConstraint::EU_WEST_1|BucketLocationConstraint::US_WEST_1|BucketLocationConstraint::US_WEST_2|BucketLocationConstraint::AP_SOUTH_1|BucketLocationConstraint::AP_SOUTHEAST_1|BucketLocationConstraint::AP_SOUTHEAST_2|BucketLocationConstraint::AP_NORTHEAST_1|BucketLocationConstraint::SA_EAST_1|BucketLocationConstraint::CN_NORTH_1|BucketLocationConstraint::EU_CENTRAL_1|null
     */
    public function getLocationConstraint(): ?string
    {
        return $this->LocationConstraint;
    }

    /**
     * @param BucketLocationConstraint::EU|BucketLocationConstraint::EU_WEST_1|BucketLocationConstraint::US_WEST_1|BucketLocationConstraint::US_WEST_2|BucketLocationConstraint::AP_SOUTH_1|BucketLocationConstraint::AP_SOUTHEAST_1|BucketLocationConstraint::AP_SOUTHEAST_2|BucketLocationConstraint::AP_NORTHEAST_1|BucketLocationConstraint::SA_EAST_1|BucketLocationConstraint::CN_NORTH_1|BucketLocationConstraint::EU_CENTRAL_1|null $value
     */
    public function setLocationConstraint(?string $value): self
    {
        $this->LocationConstraint = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null !== $this->LocationConstraint) {
            if (!isset(BucketLocationConstraint::AVAILABLE_BUCKETLOCATIONCONSTRAINT[$this->LocationConstraint])) {
                throw new InvalidArgument(sprintf('Invalid parameter "LocationConstraint" when validating the "%s". The value "%s" is not a valid "BucketLocationConstraint". Available values are %s.', __CLASS__, $this->LocationConstraint, implode(', ', array_keys(BucketLocationConstraint::AVAILABLE_BUCKETLOCATIONCONSTRAINT))));
            }
        }
    }
}
