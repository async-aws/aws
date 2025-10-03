<?php

namespace AsyncAws\Route53\ValueObject;

/**
 * A complex type that contains an optional comment about your hosted zone. If you don't want to specify a comment, omit
 * both the `HostedZoneConfig` and `Comment` elements.
 */
final class HostedZoneConfig
{
    /**
     * Any comments that you want to include about the hosted zone.
     *
     * @var string|null
     */
    private $comment;

    /**
     * A value that indicates whether this is a private hosted zone.
     *
     * @var bool|null
     */
    private $privateZone;

    /**
     * @param array{
     *   Comment?: string|null,
     *   PrivateZone?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->comment = $input['Comment'] ?? null;
        $this->privateZone = $input['PrivateZone'] ?? null;
    }

    /**
     * @param array{
     *   Comment?: string|null,
     *   PrivateZone?: bool|null,
     * }|HostedZoneConfig $input
     */
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
