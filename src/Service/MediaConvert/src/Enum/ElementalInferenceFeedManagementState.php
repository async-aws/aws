<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Elemental Inference Feed management state.
 */
final class ElementalInferenceFeedManagementState
{
    public const ASSOCIATED = 'ASSOCIATED';
    public const CREATED = 'CREATED';
    public const DELETED = 'DELETED';
    public const PENDING_DELETION = 'PENDING_DELETION';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ASSOCIATED => true,
            self::CREATED => true,
            self::DELETED => true,
            self::PENDING_DELETION => true,
        ][$value]);
    }
}
