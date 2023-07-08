<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The resource that you've entered was not found in your AWS account.
 */
final class ResourceNotFoundException extends ClientException
{
}
