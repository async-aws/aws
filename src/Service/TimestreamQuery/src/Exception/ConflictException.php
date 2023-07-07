<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * Unable to poll results for a cancelled query.
 */
final class ConflictException extends ClientException
{
}
