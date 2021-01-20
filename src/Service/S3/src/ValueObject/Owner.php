<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container for the bucket owner's display name and ID.
 */
final class Owner
{
    /**
     * Container for the display name of the owner.
     */
    private $displayName;

    /**
     * Container for the ID of the owner.
     */
    private $iD;

    /**
     * @param array{
     *   DisplayName?: null|string,
     *   ID?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->displayName = $input['DisplayName'] ?? null;
        $this->iD = $input['ID'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getID(): ?string
    {
        return $this->iD;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->displayName) {
            $node->appendChild($document->createElement('DisplayName', $v));
        }
        if (null !== $v = $this->iD) {
            $node->appendChild($document->createElement('ID', $v));
        }
    }
}
