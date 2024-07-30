<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\LocationType;

/**
 * Specifies the location where the bucket will be created.
 *
 * For directory buckets, the location type is Availability Zone. For more information about directory buckets, see
 * Directory buckets [^1] in the *Amazon S3 User Guide*.
 *
 * > This functionality is only supported by directory buckets.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-buckets-overview.html
 */
final class LocationInfo
{
    /**
     * The type of location where the bucket will be created.
     *
     * @var LocationType::*|null
     */
    private $type;

    /**
     * The name of the location where the bucket will be created.
     *
     * For directory buckets, the name of the location is the AZ ID of the Availability Zone where the bucket will be
     * created. An example AZ ID value is `usw2-az1`.
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   Type?: null|LocationType::*,
     *   Name?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
        $this->name = $input['Name'] ?? null;
    }

    /**
     * @param array{
     *   Type?: null|LocationType::*,
     *   Name?: null|string,
     * }|LocationInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return LocationType::*|null
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
        if (null !== $v = $this->type) {
            if (!LocationType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "LocationType".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Type', $v));
        }
        if (null !== $v = $this->name) {
            $node->appendChild($document->createElement('Name', $v));
        }
    }
}
