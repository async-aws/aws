<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container for the owner's display name and ID.
 */
final class Owner
{
    /**
     * @var string|null
     */
    private $displayName;

    /**
     * Container for the ID of the owner.
     *
     * @var string|null
     */
    private $id;

    /**
     * @param array{
     *   DisplayName?: string|null,
     *   ID?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->displayName = $input['DisplayName'] ?? null;
        $this->id = $input['ID'] ?? null;
    }

    /**
     * @param array{
     *   DisplayName?: string|null,
     *   ID?: string|null,
     * }|Owner $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->displayName) {
            $node->appendChild($document->createElement('DisplayName', $v));
        }
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('ID', $v));
        }
    }
}
