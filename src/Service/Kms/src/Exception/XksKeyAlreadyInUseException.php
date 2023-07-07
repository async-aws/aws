<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the (`XksKeyId`) is already associated with a KMS key in this external key store.
 * Each KMS key in an external key store must be associated with a different external key.
 */
final class XksKeyAlreadyInUseException extends ClientException
{
}
