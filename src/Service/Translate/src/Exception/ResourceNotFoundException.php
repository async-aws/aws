<?php

namespace AsyncAws\Translate\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource you are looking for has not been found. Review the resource you're looking for and see if a different
 * resource will accomplish your needs before retrying the revised request.
 */
final class ResourceNotFoundException extends ClientException
{
}
