<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the signature verification failed. Signature verification fails when it cannot
 * confirm that signature was produced by signing the specified message with the specified KMS key and signing
 * algorithm.
 */
final class KMSInvalidSignatureException extends ClientException
{
}
