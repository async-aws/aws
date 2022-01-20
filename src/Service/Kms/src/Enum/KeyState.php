<?php

namespace AsyncAws\Kms\Enum;

/**
 * The current status of the KMS key.
 * For more information about how key state affects the use of a KMS key, see Key state: Effect on your KMS key in the
 * *Key Management Service Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/key-state.html
 */
final class KeyState
{
    public const CREATING = 'Creating';
    public const DISABLED = 'Disabled';
    public const ENABLED = 'Enabled';
    public const PENDING_DELETION = 'PendingDeletion';
    public const PENDING_IMPORT = 'PendingImport';
    public const PENDING_REPLICA_DELETION = 'PendingReplicaDeletion';
    public const UNAVAILABLE = 'Unavailable';
    public const UPDATING = 'Updating';

    public static function exists(string $value): bool
    {
        return isset([
            self::CREATING => true,
            self::DISABLED => true,
            self::ENABLED => true,
            self::PENDING_DELETION => true,
            self::PENDING_IMPORT => true,
            self::PENDING_REPLICA_DELETION => true,
            self::UNAVAILABLE => true,
            self::UPDATING => true,
        ][$value]);
    }
}
