<?php

namespace AsyncAws\Iam\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A structure that represents user-provided metadata that can be associated with an IAM resource. For more information
 * about tagging, see Tagging IAM resources [^1] in the *IAM User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_tags.html
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
     *
     * > Amazon Web Services always interprets the tag `Value` as a single string. If you need to store an array, you can
     * > store comma-separated values in the string. However, you must interpret the value in your code.
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
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * }|Tag $input
     */
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
        $v = $this->key;
        $payload['Key'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;

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
