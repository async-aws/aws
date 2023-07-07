<?php

namespace AsyncAws\Kms\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request was rejected because the specified grant token is not valid.
 */
final class InvalidGrantTokenException extends ClientException
{
}
