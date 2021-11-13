<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes a tag. A tag is a key-value pair. You can add up to 50 tags to a single DynamoDB table.
 * Amazon Web Services-assigned tag names and values are automatically assigned the `aws:` prefix, which the user cannot
 * assign. Amazon Web Services-assigned tag names do not count towards the tag limit of 50. User-assigned tag names have
 * the prefix `user:` in the Cost Allocation Report. You cannot backdate the application of a tag.
 * For an overview on tagging DynamoDB resources, see Tagging for DynamoDB in the *Amazon DynamoDB Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Tagging.html
 */
final class Tag
{
    /**
     * The key of the tag. Tag keys are case sensitive. Each DynamoDB table can only have up to one tag with the same key.
     * If you try to add an existing tag (same key), the existing tag value will be updated to the new value.
     */
    private $key;

    /**
     * The value of the tag. Tag values are case-sensitive and can be null.
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Key'] = $v;
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Value'] = $v;

        return $payload;
    }
}
