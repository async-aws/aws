<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A structure that represents user-provided metadata that can be associated with an IAM resource. For more information
 * about tagging, see Tagging IAM resources in the *IAM User Guide*.
 *
 * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
 */
final class Tag
{
    /**
     * The key name that can be used to look up or retrieve the associated value. For example, `Department` or `Cost Center`
     * are common choices.
     */
    private $key;

    /**
     * The value associated with this tag. For example, tags with a key name of `Department` could have values such as
     * `Human Resources`, `Accounting`, and `Support`. Tags with a key name of `Cost Center` might have values that consist
     * of the number associated with the different cost centers in your company. Typically, many resources have tags with
     * the same key name but with different values.
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
