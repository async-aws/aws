<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A key-value pair. A tag consists of a tag key and a tag value. Tag keys and tag values are both required, but tag
 * values can be empty (null) strings.
 *
 * ! Do not include confidential or sensitive information in this field. This field may be displayed in plaintext in
 * ! CloudTrail logs and other output.
 *
 * For information about the rules that apply to tag keys and tag values, see User-Defined Tag Restrictions [^1] in the
 * *Amazon Web Services Billing and Cost Management User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/allocation-tag-restrictions.html
 */
final class Tag
{
    /**
     * The key of the tag.
     *
     * @var string
     */
    private $tagKey;

    /**
     * The value of the tag.
     *
     * @var string
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
        $this->tagKey = $input['TagKey'] ?? $this->throwException(new InvalidArgument('Missing required field "TagKey".'));
        $this->tagValue = $input['TagValue'] ?? $this->throwException(new InvalidArgument('Missing required field "TagValue".'));
    }

    /**
     * @param array{
     *   TagKey: string,
     *   TagValue: string,
     * }|Tag $input
     */
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
        $v = $this->tagKey;
        $payload['TagKey'] = $v;
        $v = $this->tagValue;
        $payload['TagValue'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
