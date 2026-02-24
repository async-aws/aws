<?php

namespace AsyncAws\S3\ValueObject;

/**
 * This is used in a Lifecycle Rule Filter to apply a logical AND to two or more predicates. The Lifecycle Rule will
 * apply to any object matching all of the predicates configured inside the And operator.
 */
final class LifecycleRuleAndOperator
{
    /**
     * Prefix identifying one or more objects to which the rule applies.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * All of these tags must exist in the object's tag set in order for the rule to apply.
     *
     * @var Tag[]|null
     */
    private $tags;

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
     * @param array{
     *   Prefix?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   ObjectSizeGreaterThan?: int|null,
     *   ObjectSizeLessThan?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->prefix = $input['Prefix'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->objectSizeGreaterThan = $input['ObjectSizeGreaterThan'] ?? null;
        $this->objectSizeLessThan = $input['ObjectSizeLessThan'] ?? null;
    }

    /**
     * @param array{
     *   Prefix?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   ObjectSizeGreaterThan?: int|null,
     *   ObjectSizeLessThan?: int|null,
     * }|LifecycleRuleAndOperator $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->prefix) {
            $node->appendChild($document->createElement('Prefix', $v));
        }
        if (null !== $v = $this->tags) {
            $node->appendChild($nodeList = $document->createElement('Tag'));
            foreach ($v as $item) {
                $nodeList->appendChild($child = $document->createElement('Tag'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->objectSizeGreaterThan) {
            $node->appendChild($document->createElement('ObjectSizeGreaterThan', (string) $v));
        }
        if (null !== $v = $this->objectSizeLessThan) {
            $node->appendChild($document->createElement('ObjectSizeLessThan', (string) $v));
        }
    }
}
