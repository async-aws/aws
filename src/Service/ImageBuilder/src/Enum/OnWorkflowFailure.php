<?php

namespace AsyncAws\ImageBuilder\Enum;

final class OnWorkflowFailure
{
    public const ABORT = 'ABORT';
    public const CONTINUE = 'CONTINUE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ABORT => true,
            self::CONTINUE => true,
        ][$value]);
    }
}
