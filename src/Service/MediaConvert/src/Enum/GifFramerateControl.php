<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If you are using the console, use the Framerate setting to specify the frame rate for this output. If you want to
 * keep the same frame rate as the input video, choose Follow source. If you want to do frame rate conversion, choose a
 * frame rate from the dropdown list or choose Custom. The framerates shown in the dropdown list are decimal
 * approximations of fractions. If you choose Custom, specify your frame rate as a fraction. If you are creating your
 * transcoding job specification as a JSON file without the console, use FramerateControl to specify which value the
 * service uses for the frame rate for this output. Choose INITIALIZE_FROM_SOURCE if you want the service to use the
 * frame rate from the input. Choose SPECIFIED if you want the service to use the frame rate you specify in the settings
 * FramerateNumerator and FramerateDenominator.
 */
final class GifFramerateControl
{
    public const INITIALIZE_FROM_SOURCE = 'INITIALIZE_FROM_SOURCE';
    public const SPECIFIED = 'SPECIFIED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INITIALIZE_FROM_SOURCE => true,
            self::SPECIFIED => true,
        ][$value]);
    }
}
