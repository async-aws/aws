<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\ValueObject\EventBridgeConfiguration as EventBridgeConfiguration1;

/**
 * A container for specifying the configuration for Amazon EventBridge.
 */
final class EventBridgeConfiguration
{
    /**
     * @param array|EventBridgeConfiguration1 $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self();
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
    }
}
