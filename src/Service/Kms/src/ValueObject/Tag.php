<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A key-value pair. A tag consists of a tag key and a tag value. Tag keys and tag values are both required, but tag
 * values can be empty (null) strings.
 * For information about the rules that apply to tag keys and tag values, see User-Defined Tag Restrictions in the
 * *Amazon Web Services Billing and Cost Management User Guide*.
 *
 * @see https://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/allocation-tag-restrictions.html
 */
final class Tag
{
    /**
     * The key of the tag.
     */
    private $tagKey;

    /**
     * The value of the tag.
     */
    private $tagValue;

    /**
     * @param array{
     *   TagKey: string,
     *   TagValue: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tagKey = $input['TagKey'] ?? null;
        $this->tagValue = $input['TagValue'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTagKey(): string
    {
        return $this->tagKey;
    }

    public function getTagValue(): string
    {
        return $this->tagValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->tagKey) {
            throw new InvalidArgument(sprintf('Missing parameter "TagKey" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TagKey'] = $v;
        if (null === $v = $this->tagValue) {
            throw new InvalidArgument(sprintf('Missing parameter "TagValue" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TagValue'] = $v;

        return $payload;
    }
}
