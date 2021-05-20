<?php

namespace AsyncAws\Rekognition\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource specified in the request cannot be found.
 */
final class ResourceNotFoundException extends ClientException
{
}
