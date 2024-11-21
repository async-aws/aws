<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You do not have the necessary permissions to access the account settings.
 */
final class AccessDeniedException extends ClientException
{
}
