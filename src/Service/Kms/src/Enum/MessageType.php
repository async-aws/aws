<?php

namespace AsyncAws\Kms\Enum;

/**
 * Tells KMS whether the value of the `Message` parameter should be hashed as part of the signing algorithm. Use `RAW`
 * for unhashed messages; use `DIGEST` for message digests, which are already hashed.
 * When the value of `MessageType` is `RAW`, KMS uses the standard signing algorithm, which begins with a hash function.
 * When the value is `DIGEST`, KMS skips the hashing step in the signing algorithm.
 *
 * ! Use the `DIGEST` value only when the value of the `Message` parameter is a message digest. If you use the `DIGEST`
 * ! value with an unhashed message, the security of the signing operation can be compromised.
 *
 * When the value of `MessageType`is `DIGEST`, the length of the `Message` value must match the length of hashed
 * messages for the specified signing algorithm.
 * You can submit a message digest and omit the `MessageType` or specify `RAW` so the digest is hashed again while
 * signing. However, this can cause verification failures when verifying with a system that assumes a single hash.
 * The hashing algorithm in that `Sign` uses is based on the `SigningAlgorithm` value.
 *
 * - Signing algorithms that end in SHA_256 use the SHA_256 hashing algorithm.
 * - Signing algorithms that end in SHA_384 use the SHA_384 hashing algorithm.
 * - Signing algorithms that end in SHA_512 use the SHA_512 hashing algorithm.
 * - SM2DSA uses the SM3 hashing algorithm. For details, see Offline verification with SM2 key pairs.
 *
 * @see https://docs.aws.amazon.com/kms/latest/developerguide/asymmetric-key-specs.html#key-spec-sm-offline-verification
 */
final class MessageType
{
    public const DIGEST = 'DIGEST';
    public const RAW = 'RAW';

    public static function exists(string $value): bool
    {
        return isset([
            self::DIGEST => true,
            self::RAW => true,
        ][$value]);
    }
}
