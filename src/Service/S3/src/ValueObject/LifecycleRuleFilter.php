<?php

namespace AsyncAws\S3\ValueObject;

/**
 * The `Filter` is used to identify objects that a Lifecycle Rule applies to. A `Filter` can have exactly one of
 * `Prefix`, `Tag`, `ObjectSizeGreaterThan`, `ObjectSizeLessThan`, or `And` specified. If the `Filter` element is left
 * empty, the Lifecycle Rule applies to all objects in the bucket.
 */
final class LifecycleRuleFilter
{
    /**
     * Prefix identifying one or more objects to which the rule applies.
     *
     * ! Replacement must be made for object keys containing special characters (such as carriage returns) when using XML
     * ! requests. For more information, see XML related object key constraints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html#object-key-xml-related-constraints
     *
     * @var string|null
     */
    private $prefix;

    /**
     * This tag must exist in the object's tag set in order for the rule to apply.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var Tag|null
     */
    private $tag;

    /**
     * Minimum object size to which the rule applies.
     *
     * @var int|null
     */
    private $objectSizeGreaterThan;

    /**
     * Maximum object size to which the rule applies.
     *
     * @var int|null
     */
    private $objectSizeLessThan;

    /**
     * @var LifecycleRuleAndOperator|null
     */
    private $and;

    /**
     * @param array{
     *   Prefix?: string|null,
     *   Tag?: Tag|array|null,
     *   ObjectSizeGreaterThan?: int|null,
     *   ObjectSizeLessThan?: int|null,
     *   And?: LifecycleRuleAndOperator|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->prefix = $input['Prefix'] ?? null;
        $this->tag = isset($input['Tag']) ? Tag::create($input['Tag']) : null;
        $this->objectSizeGreaterThan = $input['ObjectSizeGreaterThan'] ?? null;
        $this->objectSizeLessThan = $input['ObjectSizeLessThan'] ?? null;
        $this->and = isset($input['And']) ? LifecycleRuleAndOperator::create($input['And']) : null;
    }

    /**
     * @param array{
     *   Prefix?: string|null,
     *   Tag?: Tag|array|null,
     *   ObjectSizeGreaterThan?: int|null,
     *   ObjectSizeLessThan?: int|null,
     *   And?: LifecycleRuleAndOperator|array|null,
     * }|LifecycleRuleFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnd(): ?LifecycleRuleAndOperator
    {
        return $this->and;
    }

    public function getObjectSizeGreaterThan(): ?int
    {
        return $this->objectSizeGreaterThan;
    }

    public function getObjectSizeLessThan(): ?int
    {
        return $this->objectSizeLessThan;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->prefix) {
            $node->appendChild($document->createElement('Prefix', $v));
        }
        if (null !== $v = $this->tag) {
            $node->appendChild($child = $document->createElement('Tag'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->objectSizeGreaterThan) {
            $node->appendChild($document->createElement('ObjectSizeGreaterThan', (string) $v));
        }
        if (null !== $v = $this->objectSizeLessThan) {
            $node->appendChild($document->createElement('ObjectSizeLessThan', (string) $v));
        }
        if (null !== $v = $this->and) {
            $node->appendChild($child = $document->createElement('And'));

            $v->requestBody($child, $document);
        }
    }
}
