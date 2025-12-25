<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected for one of the following reasons:
 *
 * - The `KeyUsage` value of the KMS key is incompatible with the API operation.
 * - The encryption algorithm or signing algorithm specified for the operation is incompatible with the type of key
 *   material in the KMS key (`KeySpec`).
 *
 * For more information, see InvalidKeyUsageException [^1] in the *Amazon Web Services Key Management Service API
 * Reference*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/APIReference/API_Encrypt.html#API_Encrypt_Errors
 */
final class KmsInvalidKeyUsageException extends ClientException
{
}
