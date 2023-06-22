<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Container for `TagSet` elements.
 */
final class Tagging
{
    /**
     * A collection for a set of tags.
     */
    private $tagSet;

    /**
     * @param array{
     *   TagSet: Tag[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tagSet = isset($input['TagSet']) ? array_map([Tag::class, 'create'], $input['TagSet']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Tag[]
     */
    public function getTagSet(): array
    {
        return $this->tagSet ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null === $v = $this->tagSet) {
            throw new InvalidArgument(sprintf('Missing parameter "TagSet" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($nodeList = $document->createElement('TagSet'));
        foreach ($v as $item) {
            $nodeList->appendChild($child = $document->createElement('Tag'));

            $item->requestBody($child, $document);
        }
    }
}
