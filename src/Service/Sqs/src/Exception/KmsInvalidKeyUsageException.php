<?php

namespace AsyncAws\Sqs\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected for one of the following reasons:
 *
 * - The KeyUsage value of the KMS key is incompatible with the API operation.
 * - The encryption algorithm or signing algorithm specified for the operation is incompatible with the type of key
 *   material in the KMS key (KeySpec).
 */
final class KmsInvalidKeyUsageException extends ClientException
{
}
