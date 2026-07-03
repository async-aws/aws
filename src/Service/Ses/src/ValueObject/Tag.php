<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An object that defines the tags that are associated with a resource. A *tag* is a label that you optionally define
 * and associate with a resource. Tags can help you categorize and manage resources in different ways, such as by
 * purpose, owner, environment, or other criteria. A resource can have as many as 50 tags.
 *
 * Each tag consists of a required *tag key* and an associated *tag value*, both of which you define. A tag key is a
 * general label that acts as a category for a more specific tag value. A tag value acts as a descriptor within a tag
 * key. A tag key can contain as many as 128 characters. A tag value can contain as many as 256 characters. The
 * characters can be Unicode letters, digits, white space, or one of the following symbols: _ . : / = + -. The following
 * additional restrictions apply to tags:
 *
 * - Tag keys and values are case sensitive.
 * - For each associated resource, each tag key must be unique and it can have only one value.
 * - The `aws:` prefix is reserved for use by Amazon Web Services; you can’t use it in any tag keys or values that
 *   you define. In addition, you can't edit or remove tag keys or values that use this prefix. Tags that use this
 *   prefix don’t count against the limit of 50 tags per resource.
 * - You can associate tags with public or shared resources, but the tags are available only for your Amazon Web
 *   Services account, not any other accounts that share the resource. In addition, the tags are available only for
 *   resources that are located in the specified Amazon Web Services Region for your Amazon Web Services account.
 */
final class Tag
{
    /**
     * One part of a key-value pair that defines a tag. The maximum length of a tag key is 128 characters. The minimum
     * length is 1 character.
     *
     * @var string
     */
    private $key;

    /**
     * The optional part of a key-value pair that defines a tag. The maximum length of a tag value is 256 characters. The
     * minimum length is 0 characters. If you don't want a resource to have a specific tag value, don't specify a value for
     * this parameter. If you don't specify a value, Amazon SES sets the value to an empty string.
     *
     * @var string
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
