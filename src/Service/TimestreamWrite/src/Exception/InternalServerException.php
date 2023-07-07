<?php

namespace AsyncAws\TimestreamWrite\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Timestream was unable to fully process this request because of an internal server error.
 */
final class InternalServerException extends ClientException
{
}
