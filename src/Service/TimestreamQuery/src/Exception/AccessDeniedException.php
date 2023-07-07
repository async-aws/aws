<?php

namespace AsyncAws\TimestreamQuery\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You are not authorized to perform this action.
 */
final class AccessDeniedException extends ClientException
{
}
