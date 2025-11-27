<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Respond to AFD to specify how the service changes the video itself in response to AFD values in the input. *
 * Choose Respond to clip the input video frame according to the AFD value, input display aspect ratio, and output
 * display aspect ratio. * Choose Passthrough to include the input AFD values. Do not choose this when AfdSignaling is
 * set to NONE. A preferred implementation of this workflow is to set RespondToAfd to and set AfdSignaling to AUTO. *
 * Choose None to remove all input AFD values from this output.
 */
final class RespondToAfd
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const RESPOND = 'RESPOND';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
            self::RESPOND => true,
        ][$value]);
    }
}
