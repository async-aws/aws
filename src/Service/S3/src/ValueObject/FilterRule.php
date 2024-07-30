<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\FilterRuleName;

/**
 * Specifies the Amazon S3 object key name to filter on. An object key name is the name assigned to an object in your
 * Amazon S3 bucket. You specify whether to filter on the suffix or prefix of the object key name. A prefix is a
 * specific string of characters at the beginning of an object key name, which you can use to organize objects. For
 * example, you can start the key names of related objects with a prefix, such as `2023-` or `engineering/`. Then, you
 * can use `FilterRule` to find objects in a bucket with key names that have the same prefix. A suffix is similar to a
 * prefix, but it is at the end of the object key name instead of at the beginning.
 */
final class FilterRule
{
    /**
     * The object key name prefix or suffix identifying one or more objects to which the filtering rule applies. The maximum
     * length is 1,024 characters. Overlapping prefixes and suffixes are not supported. For more information, see
     * Configuring Event Notifications [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     *
     * @var FilterRuleName::*|null
     */
    private $name;

    /**
     * The value that the filter searches for in object key names.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Name?: null|FilterRuleName::*,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Name?: null|FilterRuleName::*,
     *   Value?: null|string,
     * }|FilterRule $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FilterRuleName::*|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->name) {
            if (!FilterRuleName::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Name" for "%s". The value "%s" is not a valid "FilterRuleName".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Name', $v));
        }
        if (null !== $v = $this->value) {
            $node->appendChild($document->createElement('Value', $v));
        }
    }
}
