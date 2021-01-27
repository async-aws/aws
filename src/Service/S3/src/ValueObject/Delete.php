<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Container for the request.
 */
final class Delete
{
    /**
     * The objects to delete.
     */
    private $objects;

    /**
     * Element to enable quiet mode for the request. When you add this element, you must set its value to true.
     */
    private $quiet;

    /**
     * @param array{
     *   Objects: ObjectIdentifier[],
     *   Quiet?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->objects = isset($input['Objects']) ? array_map([ObjectIdentifier::class, 'create'], $input['Objects']) : null;
        $this->quiet = $input['Quiet'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectIdentifier[]
     */
    public function getObjects(): array
    {
        return $this->objects ?? [];
    }

    public function getQuiet(): ?bool
    {
        return $this->quiet;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->objects) {
            throw new InvalidArgument(sprintf('Missing parameter "Objects" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            $node->appendChild($child = $document->createElement('Object'));

            $item->requestBody($child, $document);
        }

        if (null !== $v = $this->quiet) {
            $node->appendChild($document->createElement('Quiet', $v ? 'true' : 'false'));
        }
    }
}
