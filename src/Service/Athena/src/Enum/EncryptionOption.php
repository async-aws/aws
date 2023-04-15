<?php

namespace AsyncAws\Athena\Enum;

/**
 * Indicates whether Amazon S3 server-side encryption with Amazon S3-managed keys (`SSE_S3`), server-side encryption
 * with KMS-managed keys (`SSE_KMS`), or client-side encryption with KMS-managed keys (`CSE_KMS`) is used.
 * If a query runs in a workgroup and the workgroup overrides client-side settings, then the workgroup's setting for
 * encryption is used. It specifies whether query results must be encrypted, for all queries that run in this workgroup.
 */
final class EncryptionOption
{
    public const CSE_KMS = 'CSE_KMS';
    public const SSE_KMS = 'SSE_KMS';
    public const SSE_S3 = 'SSE_S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::CSE_KMS => true,
            self::SSE_KMS => true,
            self::SSE_S3 => true,
        ][$value]);
    }
}
