<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified KMS key was not available. You can retry the request.
 */
final class KeyUnavailableException extends ClientException
{
}
