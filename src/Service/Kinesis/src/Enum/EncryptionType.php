<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * The server-side encryption type used on the stream. This parameter can be one of the following values:.
 *
 * - `NONE`: Do not encrypt the records in the stream.
 * - `KMS`: Use server-side encryption on the records in the stream using a customer-managed Amazon Web Services KMS
 *   key.
 */
final class EncryptionType
{
    public const KMS = 'KMS';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::KMS => true,
            self::NONE => true,
        ][$value]);
    }
}
