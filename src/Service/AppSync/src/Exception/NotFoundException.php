<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource specified in the request was not found. Check the resource, and then try again.
 */
final class NotFoundException extends ClientException
{
}
