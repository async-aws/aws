<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To add an InbandEventStream element in your output MPD manifest for each type of event message, set Manifest metadata
 * signaling to Enabled. For ID3 event messages, the InbandEventStream element schemeIdUri will be same value that you
 * specify for ID3 metadata scheme ID URI. For SCTE35 event messages, the InbandEventStream element schemeIdUri will be
 * "urn:scte:scte35:2013:bin". To leave these elements out of your output MPD manifest, set Manifest metadata signaling
 * to Disabled. To enable Manifest metadata signaling, you must also set SCTE-35 source to Passthrough, ESAM SCTE-35 to
 * insert, or ID3 metadata to Passthrough.
 */
final class MpdManifestMetadataSignaling
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
