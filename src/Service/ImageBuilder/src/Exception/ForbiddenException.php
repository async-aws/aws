<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * You are not authorized to perform the requested operation.
 */
final class ForbiddenException extends ClientException
{
}
