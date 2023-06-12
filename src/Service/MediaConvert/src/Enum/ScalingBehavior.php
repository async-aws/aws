<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how the service handles outputs that have a different aspect ratio from the input aspect ratio. Choose
 * Stretch to output (STRETCH_TO_OUTPUT) to have the service stretch your video image to fit. Keep the setting Default
 * (DEFAULT) to have the service letterbox your video instead. This setting overrides any value that you specify for the
 * setting Selection placement (position) in this output.
 */
final class ScalingBehavior
{
    public const DEFAULT = 'DEFAULT';
    public const STRETCH_TO_OUTPUT = 'STRETCH_TO_OUTPUT';

    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::STRETCH_TO_OUTPUT => true,
        ][$value]);
    }
}
