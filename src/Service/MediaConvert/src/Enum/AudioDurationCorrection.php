<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Apply audio timing corrections to help synchronize audio and video in your output. To apply timing corrections, your
 * input must meet the following requirements: * Container: MP4, or MOV, with an accurate time-to-sample (STTS) table. *
 * Audio track: AAC. Choose from the following audio timing correction settings: * Disabled (Default): Apply no
 * correction. * Auto: Recommended for most inputs. MediaConvert analyzes the audio timing in your input and determines
 * which correction setting to use, if needed. * Track: Adjust the duration of each audio frame by a constant amount to
 * align the audio track length with STTS duration. Track-level correction does not affect pitch, and is recommended for
 * tonal audio content such as music. * Frame: Adjust the duration of each audio frame by a variable amount to align
 * audio frames with STTS timestamps. No corrections are made to already-aligned frames. Frame-level correction may
 * affect the pitch of corrected frames, and is recommended for atonal audio content such as speech or percussion. *
 * Force: Apply audio duration correction, either Track or Frame depending on your input, regardless of the accuracy of
 * your input's STTS table. Your output audio and video may not be aligned or it may contain audio artifacts.
 */
final class AudioDurationCorrection
{
    public const AUTO = 'AUTO';
    public const DISABLED = 'DISABLED';
    public const FORCE = 'FORCE';
    public const FRAME = 'FRAME';
    public const TRACK = 'TRACK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DISABLED => true,
            self::FORCE => true,
            self::FRAME => true,
            self::TRACK => true,
        ][$value]);
    }
}
