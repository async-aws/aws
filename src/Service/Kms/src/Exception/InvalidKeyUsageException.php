<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected for one of the following reasons:
 *
 * - The `KeyUsage` value of the KMS key is incompatible with the API operation.
 * - The encryption algorithm or signing algorithm specified for the operation is incompatible with the type of key
 *   material in the KMS key `(KeySpec`).
 *
 * For encrypting, decrypting, re-encrypting, and generating data keys, the `KeyUsage` must be `ENCRYPT_DECRYPT`. For
 * signing and verifying messages, the `KeyUsage` must be `SIGN_VERIFY`. For generating and verifying message
 * authentication codes (MACs), the `KeyUsage` must be `GENERATE_VERIFY_MAC`. For deriving key agreement secrets, the
 * `KeyUsage` must be `KEY_AGREEMENT`. To find the `KeyUsage` of a KMS key, use the DescribeKey operation.
 *
 * To find the encryption or signing algorithms supported for a particular KMS key, use the DescribeKey operation.
 */
final class InvalidKeyUsageException extends ClientException
{
}
