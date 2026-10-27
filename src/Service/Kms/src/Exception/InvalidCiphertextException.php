<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * From the Decrypt or ReEncrypt operation, the request was rejected because the specified ciphertext, or additional
 * authenticated data incorporated into the ciphertext, such as the encryption context, is corrupted, missing, or
 * otherwise invalid.
 *
 * From the ImportKeyMaterial operation, the request was rejected because KMS could not decrypt the encrypted (wrapped)
 * key material.
 *
 * From the Decapsulate operation, the request was rejected because the length of the `SharedSecretCiphertextBlob` is
 * inconsistent with the specified ML-KEM key spec.
 */
final class InvalidCiphertextException extends ClientException
{
}
