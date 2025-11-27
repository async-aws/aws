<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting only in DASH output groups that include sidecar TTML, IMSC or WEBVTT captions. You specify sidecar
 * captions in a separate output from your audio and video. Choose Raw for captions in a single XML file in a raw
 * container. Choose Fragmented MPEG-4 for captions in XML format contained within fragmented MP4 files. This set of
 * fragmented MP4 files is separate from your video and audio fragmented MP4 files.
 */
final class MpdCaptionContainerType
{
    public const FRAGMENTED_MP4 = 'FRAGMENTED_MP4';
    public const RAW = 'RAW';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FRAGMENTED_MP4 => true,
            self::RAW => true,
        ][$value]);
    }
}
