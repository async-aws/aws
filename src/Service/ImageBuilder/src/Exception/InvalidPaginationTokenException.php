<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You have provided an invalid pagination token in your request.
 */
final class InvalidPaginationTokenException extends ClientException
{
}
