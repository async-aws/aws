<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\BucketType;
use AsyncAws\S3\Enum\DataRedundancy;

/**
 * Specifies the information about the bucket that will be created. For more information about directory buckets, see
 * Directory buckets [^1] in the *Amazon S3 User Guide*.
 *
 * > This functionality is only supported by directory buckets.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-overview.html
 */
final class BucketInfo
{
    /**
     * The number of Availability Zone that's used for redundancy for the bucket.
     *
     * @var DataRedundancy::*|null
     */
    private $dataRedundancy;

    /**
     * The type of bucket.
     *
     * @var BucketType::*|null
     */
    private $type;

    /**
     * @param array{
     *   DataRedundancy?: null|DataRedundancy::*,
     *   Type?: null|BucketType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataRedundancy = $input['DataRedundancy'] ?? null;
        $this->type = $input['Type'] ?? null;
    }

    /**
     * @param array{
     *   DataRedundancy?: null|DataRedundancy::*,
     *   Type?: null|BucketType::*,
     * }|BucketInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DataRedundancy::*|null
     */
    public function getDataRedundancy(): ?string
    {
        return $this->dataRedundancy;
    }

    /**
     * @return BucketType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->dataRedundancy) {
            if (!DataRedundancy::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "DataRedundancy" for "%s". The value "%s" is not a valid "DataRedundancy".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('DataRedundancy', $v));
        }
        if (null !== $v = $this->type) {
            if (!BucketType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "BucketType".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Type', $v));
        }
    }
}
