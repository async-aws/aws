<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Enables delivery of events to Amazon EventBridge.
 */
final class EventBridgeConfiguration
{
    public function __construct(array $input)
    {
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
    }
}
