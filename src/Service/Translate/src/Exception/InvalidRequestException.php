<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request that you made is not valid. Check your request to determine why it's not valid and then retry the
 * request.
 */
final class InvalidRequestException extends ClientException
{
}
