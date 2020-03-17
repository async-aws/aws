<?php

namespace AsyncAws\S3\ValueObject;

class Owner
{
    /**
     * Container for the display name of the owner.
     */
    private $DisplayName;

    /**
     * Container for the ID of the owner.
     */
    private $ID;

    /**
     * @param array{
     *   DisplayName?: null|string,
     *   ID?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->DisplayName = $input['DisplayName'] ?? null;
        $this->ID = $input['ID'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->DisplayName) {
            $node->appendChild($document->createElement('DisplayName', $v));
        }
        if (null !== $v = $this->ID) {
            $node->appendChild($document->createElement('ID', $v));
        }
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
