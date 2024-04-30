<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The service was unable to fully process this request because of an internal server error.
 */
final class InternalServerException extends ClientException
{
}
