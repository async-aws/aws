<?php

namespace AsyncAws\ImageBuilder\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * At least one of the resources referenced by your request does not exist.
 */
final class ResourceNotFoundException extends ClientException
{
}
