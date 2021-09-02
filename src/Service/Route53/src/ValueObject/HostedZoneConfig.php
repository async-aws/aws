<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * (Optional) A complex type that contains the following optional values:.
 *
 * - For public and private hosted zones, an optional comment
 * - For private hosted zones, an optional `PrivateZone` element
 *
 * If you don't specify a comment or the `PrivateZone` element, omit `HostedZoneConfig` and the other elements.
 */
final class HostedZoneConfig
{
    /**
     * Any comments that you want to include about the hosted zone.
     */
    private $comment;

    /**
     * A value that indicates whether this is a private hosted zone.
     */
    private $privateZone;

    /**
     * @param array{
     *   Comment?: null|string,
     *   PrivateZone?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->comment = $input['Comment'] ?? null;
        $this->privateZone = $input['PrivateZone'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPrivateZone(): ?bool
    {
        return $this->privateZone;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->comment) {
            $node->appendChild($document->createElement('Comment', $v));
        }
        if (null !== $v = $this->privateZone) {
            $node->appendChild($document->createElement('PrivateZone', $v ? 'true' : 'false'));
        }
    }
}
