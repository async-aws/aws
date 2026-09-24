<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The request is malformed or otherwise invalid. Verify the request and try again.
 */
final class InvalidRequestException extends ClientException
{
}
