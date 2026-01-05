<?php

namespace AsyncAws\S3Vectors\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The key state of the KMS key isn't compatible with the operation.
 *
 * For more information, see KMSInvalidStateException [^1] in the *Amazon Web Services Key Management Service API
 * Reference*.
 *
 * [^1]: https://docs.aws.amazon.com/kms/latest/APIReference/API_Encrypt.html#API_Encrypt_Errors
 */
final class KmsInvalidStateException extends ClientException
{
}
