<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class Delete
{
    /**
     * The objects to delete.
     */
    private $Objects;

    /**
     * Element to enable quiet mode for the request. When you add this element, you must set its value to true.
     */
    private $Quiet;

    /**
     * @param array{
     *   Objects: \AsyncAws\S3\ValueObject\ObjectIdentifier[],
     *   Quiet?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Objects = array_map([ObjectIdentifier::class, 'create'], $input['Objects'] ?? []);
        $this->Quiet = $input['Quiet'] ?? null;
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
        return $this->Objects;
    }

    public function getQuiet(): ?bool
    {
        return $this->Quiet;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->Objects) {
            throw new InvalidArgument(sprintf('Missing parameter "Objects" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            $node->appendChild($child = $document->createElement('Object'));

            $item->requestBody($child, $document);
        }

        if (null !== $v = $this->Quiet) {
            $node->appendChild($document->createElement('Quiet', $v ? 'true' : 'false'));
        }
    }
}
