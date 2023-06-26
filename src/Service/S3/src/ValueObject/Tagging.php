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
     *
     * @var Tag[]
     */
    private $tagSet;

    /**
     * @param array{
     *   TagSet: array<Tag|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tagSet = isset($input['TagSet']) ? array_map([Tag::class, 'create'], $input['TagSet']) : $this->throwException(new InvalidArgument('Missing required field "TagSet".'));
    }

    /**
     * @param array{
     *   TagSet: array<Tag|array>,
     * }|Tagging $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Tag[]
     */
    public function getTagSet(): array
    {
        return $this->tagSet;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->tagSet;

        $node->appendChild($nodeList = $document->createElement('TagSet'));
        foreach ($v as $item) {
            $nodeList->appendChild($child = $document->createElement('Tag'));

            $item->requestBody($child, $document);
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
